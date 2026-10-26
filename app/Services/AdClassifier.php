<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

/**
 * Decides whether a text is a currency exchange ad.
 *
 * Most texts are resolved locally. Gemini is called only when the local
 * triage cannot make a confident decision. Results are cached by text hash,
 * so reposts across channels do not spend another request.
 */
class AdClassifier
{
    public function __construct(
        private readonly AdHeuristics $heuristics,
        private readonly GeminiClientPool $gemini
    ) {
    }

    /**
     * @return array{is_ad: bool, source: string, reason: string}
     */
    public function classify(string $text, string $type = '', float $rate = 0.0, string $phones = ''): array
    {
        $cacheKey = 'ad_classifier:' . sha1($this->heuristics->normalize($text));

        $cached = Cache::get($cacheKey);
        if (is_array($cached)) {
            return $cached;
        }

        $local = $this->heuristics->decide($text, $type, $rate, $phones);

        if ($local['decision'] === AdHeuristics::ACCEPT) {
            $result = ['is_ad' => true, 'source' => 'heuristic', 'reason' => $local['reason']];
        } elseif ($local['decision'] === AdHeuristics::REJECT) {
            $result = ['is_ad' => false, 'source' => 'heuristic', 'reason' => $local['reason']];
        } else {
            $llm = $this->gemini->classify($text);

            if ($llm === null) {
                $result = [
                    'is_ad' => (bool) config('gemini.fail_open', true),
                    'source' => 'fail_open',
                    'reason' => 'all_providers_failed',
                ];
            } else {
                $result = ['is_ad' => $llm, 'source' => 'llm', 'reason' => 'ambiguous'];
            }
        }

        $ttl = $result['source'] === 'fail_open'
            ? now()->addMinutes(5)
            : now()->addDays(max(1, (int) config('gemini.cache_ttl_days', 30)));

        Cache::put($cacheKey, $result, $ttl);

        return $result;
    }
}
