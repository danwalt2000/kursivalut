<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PostAdsController;

class ModerationCleanup extends Command
{
    protected $signature = 'moderation:cleanup';

    protected $description = 'Удаляет модерационные сообщения, опубликованные более 5 минут назад';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cutoff = now()->subMinutes(5);
        $rows = DB::table('moderation_messages')->where('created_at', '<', $cutoff)->get();

        foreach ($rows as $row) {
            $url = env("TG_BOT_DOMAIN") . "/api/channels.deleteMessages/?data[channel]=@" . env("TG_CHANNEL_DOMAIN") . $row->locale . "&data[id][0]=" . $row->message_id;

            try {
                PostAdsController::sendHttp($url);
                DB::table('moderation_messages')->where('id', $row->id)->delete();
            } catch (\Exception $e) {
                Log::error('Ошибка удаления модерационного сообщения: ' . $e->getMessage());
            }
        }

        $this->info("Обработано модерационных сообщений: " . count($rows));
        return 0;
    }
}