<?php

namespace App\Services;

/**
 * Cheap local triage for currency ads.
 *
 * The regex in ParseAdsController has already matched a buy/sell/exchange
 * pattern, so this class does not try to detect currency names or rates.
 * It only decides whether to trust the match, reject obvious non-ads, or
 * ask an LLM when the text is ambiguous.
 */
class AdHeuristics
{
    public const ACCEPT = 'accept';
    public const REJECT = 'reject';
    public const ASK_LLM = 'llm';

    /**
     * @return array{decision: string, score: int, reason: string}
     */
    public function decide(string $text, string $type = '', float $rate = 0.0, string $phones = ''): array
    {
        $text = $this->normalize($text);

        $hasRate = $rate > 0;
        $hasPhone = trim($phones) !== '';
        $hasContact = $this->hasContact($text);
        $hasStrongSignal = $hasRate || $hasPhone || $hasContact;

        // "Продам авто за доллары" also matches the currency regex, but it
        // is not a currency exchange ad.
        if ($this->looksLikeOtherCategory($text)) {
            return ['decision' => self::REJECT, 'score' => -10, 'reason' => 'other_category'];
        }

        $negative = $this->hasNegativeMarker($text);

        if ($negative && !$hasStrongSignal) {
            return ['decision' => self::REJECT, 'score' => -4, 'reason' => 'negative'];
        }

        // A news/offtopic message with a rate or a phone number is rare;
        // let the LLM resolve it instead of guessing.
        if ($negative && $hasStrongSignal) {
            return ['decision' => self::ASK_LLM, 'score' => 0, 'reason' => 'negative_ambiguous'];
        }

        if ($hasStrongSignal) {
            return [
                'decision' => self::ACCEPT,
                'score' => 4,
                'reason' => $this->strongReason($hasRate, $hasPhone, $hasContact),
            ];
        }

        // Short messages that matched the currency pattern are almost always
        // real offers ("Куплю USDT", "Продам доллар, курс в ЛС").
        if (mb_strlen($text) <= 250) {
            return ['decision' => self::ACCEPT, 'score' => 2, 'reason' => 'short_ad'];
        }

        return ['decision' => self::ASK_LLM, 'score' => 0, 'reason' => 'long_ambiguous'];
    }

    public function normalize(string $text): string
    {
        $text = str_replace("\u{00A0}", ' ', $text);
        $text = mb_strtolower($text, 'UTF-8');
        $text = str_replace('ё', 'е', $text);
        $text = preg_replace('/\s+/u', ' ', $text) ?? $text;

        return trim($text);
    }

    private function strongReason(bool $hasRate, bool $hasPhone, bool $hasContact): string
    {
        $reasons = [];
        if ($hasRate) {
            $reasons[] = 'rate';
        }
        if ($hasPhone) {
            $reasons[] = 'phone';
        }
        if ($hasContact) {
            $reasons[] = 'contact';
        }

        return implode(',', $reasons);
    }

    private function hasContact(string $text): bool
    {
        return (bool) preg_match(
            '/(?:@[a-z0-9_]{4,}|t\.me\/|wa\.me\/|viber|whatsapp|телеграм|телега|лс(?:\s|$|[,.)!?])|личк|пиш|звон|тел(?:ефон)?(?:\s|[.,:!?]|$)|номер\s+тел)/iu',
            $text
        );
    }

    private function hasNegativeMarker(string $text): bool
    {
        return (bool) preg_match(
            '/(?:цб\s*рф|официальн\w*\s+курс|новост|политик|войн[аеы]|сводк|розыгрыш|конкурс|ваканс|требуется|обучени|инвестиц|заработок|реклам|подписыва|голосован|петици|митинг|криминал|происшеств)/iu',
            $text
        );
    }

    private function looksLikeOtherCategory(string $text): bool
    {
        return (bool) preg_match(
            '/(?<![\p{L}])(?:продам|продаю|куплю|покупаю|сдам|сниму)\s+(?:квартир|авто|машин|мотоцикл|телефон|смартфон|iphone|айфон|ноутбук|компьютер|квартиру|машину)/iu',
            $text
        );
    }
}
