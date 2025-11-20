<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Controllers\GetAdsController;
use Illuminate\Support\Facades\Log;

class ProcessTelegramData extends Command
{
    protected $signature = 'telegram:process {data} {--my-group}'; // Принимает JSON-строку
    protected $description = 'Обрабатывает данные, полученные от Telegram, в фоне.';
     /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle(): int
    {
        $jsonString = $this->argument('data');
        $data = json_decode($jsonString); // <-- Декодируем в объект, чтобы соответствовать методу контроллера
        $isMyGroup = !empty($this->option('my-group')) ? true : false;

        // Log::info('Получены данные Telegram для обработки в команде Artisan: ' . $jsonString, $isMyGroup);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('Ошибка декодирования JSON в команде Artisan: ' . $jsonString);
            $this->error('Ошибка декодирования JSON.'); // Вывод в консоль
            return 1;
        }

        $get_ads_controller = new GetAdsController;

        try {
            $result = $get_ads_controller->addPostByShellCommand($data, $isMyGroup);
            $message = isset($data->message->message) ? $data->message->message : '';
            // пишет в логи telegram-web-сервера
            var_dump(json_encode($result), $message);

            // Если метод не вернул null/false, считаем, что все хорошо
            if ($result) return 0; 
            
            // Если контроллер вернул пустое или неудачное значение, можно это логировать
            echo('Обработка данных завершена, но результат пуст/неудачен.');
            return 0; // Считаем, что это не критическая ошибка
            
        } catch (\Exception $e) {
            Log::error('Ошибка обработки данных Telegram в контроллере: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            echo('Критическая ошибка при обработке данных.');
            return 1; // Критическая ошибка
        }
    }
}