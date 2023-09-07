<?php

/**
 * Get all updates from MadelineProto EventHandler running inside TelegramApiServer via websocket
 * @see \TelegramApiServer\Controllers\EventsController
 */

use Amp\Websocket\Client\WebsocketHandshake;
use function Amp\async;
use function Amp\delay;
use function Amp\Websocket\Client\connect;

require 'vendor/autoload.php';


$shortopts = 'u::';
$longopts = [
    'url::',
];
$options = getopt($shortopts, $longopts);
$options = [
    'url' => $options['url'] ?? $options['u'] ?? 'ws://127.0.0.1:9503/events',
];

echo "Connecting to: {$options['url']}" . PHP_EOL;

async(function () use ($options) {
    while (true) {
        try {
            $handshake = (new WebsocketHandshake($options['url']));

            $connection = connect($handshake);

            $connection->onClose(static function () use ($connection) {
                if ($connection->isClosed()) {
                    printf("Connection closed. Reason: %s\n", $connection->getCloseReason());
                }
            });

            echo 'Waiting for events...' . PHP_EOL;
            while ($message = $connection->receive()) {
                $payload = $message->buffer();

                $json = json_decode($payload);
                if (!empty($json->result) &&
                    !empty($json->result->update) &&
                    !empty($json->result->update->_) &&
                    "updateNewChannelMessage" == $json->result->update->_) {
                    $ad = $json->result->update;
                    $content = json_encode($ad);

                    $url = 'https://' . env("APP_URL") . '/api/posts/add';

                    $curl_handle = curl_init();
                    curl_setopt($curl_handle, CURLOPT_URL, $url);
                    curl_setopt($curl_handle, CURLOPT_POST, 1);
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, [
                        "password" => env('API_PASSWORD'),
                        "content" => $content]);
                    curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                    $query = curl_exec($curl_handle);
                    curl_close($curl_handle);
                    var_dump($query);
                    printf("[%s] Received event: %s\n", date('Y-m-d H:i:s'), $payload);
                }
            }
        } catch (Throwable $e) {
            printf("Error: %s\n", $e->getMessage());
        }
        delay(0.1);

    }
});

$signal = Amp\trapSignal([SIGINT, SIGTERM]);
