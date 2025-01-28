<?php

/**
 * Get all updates from MadelineProto EventHandler running inside TelegramApiServer via websocket
 * @see \TelegramApiServer\Controllers\EventsController
 */

// commands

// repost 
// http://127.0.0.1:9503/api/messages.forwardMessages/?data[from_peer]=@obmenkadn&data[to_peer]=@just_test_group_34235325&data[id][0]=977324

// send message
// http://127.0.0.1:9503/api/messages.sendMessage/?data[peer]=@just_test_group_34235325&data[message]=%D0%9A%D1%83%D0%BF%D0%BB%D1%8E-%D0%9F%D1%80%D0%BE%D0%B4%D0%B0%D0%BC+%D0%B3%D1%80%D0%B8%D0%B2%D0%BD%D1%83+%F0%9F%87%BA%F0%9F%87%A6+%D0%B7%D0%B0+%D1%80%D1%83%D0%B1+%F0%9F%87%B7%F0%9F%87%BA+%D0%BD%D0%B0%D0%BB/%D0%B1%D0%B5%D0%B7%D0%BD%D0%B0%D0%BB%0A2.38-2.48%E2%82%BD+%D0%B7%D0%B0+1%D0%B3%D1%80%D0%B8%D0%B2%D0%BD%D1%83+(%D0%BA%D1%83%D1%80%D1%81+%D0%BE%D1%82+%D0%BE%D0%B1%D1%8A%D0%B5%D0%BC%D0%B0)+%E2%9D%97%EF%B8%8F%F0%9F%92%A3+%D0%92%D0%A1%D0%A2%D0%A0%D0%95%D0%A7%D0%90+%F0%9F%92%A3%E2%9D%97%EF%B8%8F%20%F0%9F%9A%98
// http://127.0.0.1:9504/api/messages.sendMessage/?data[peer]=@kursivalut_ru_donetsk&data[message]=%D0%9A%D1%83%D0%BF%D0%BB%D1%8E-%D0%9F%D1%80%D0%BE%D0%B4%D0%B0%D0%BC+%D0%B3%D1%80%D0%B8%D0%B2%D0%BD%D1%83+%F0%9F%87%BA%F0%9F%87%A6+%D0%B7%D0%B0+%D1%80%D1%83%D0%B1+%F0%9F%87%B7%F0%9F%87%BA+%D0%BD%D0%B0%D0%BB/%D0%B1%D0%B5%D0%B7%D0%BD%D0%B0%D0%BB%0A2.38-2.48%E2%82%BD+%D0%B7%D0%B0+1%D0%B3%D1%80%D0%B8%D0%B2%D0%BD%D1%83+(%D0%BA%D1%83%D1%80%D1%81+%D0%BE%D1%82+%D0%BE%D0%B1%D1%8A%D0%B5%D0%BC%D0%B0)+%E2%9D%97%EF%B8%8F%F0%9F%92%A3+%D0%92%D0%A1%D0%A2%D0%A0%D0%95%D0%A7%D0%90+%F0%9F%92%A3%E2%9D%97%EF%B8%8F%20%F0%9F%9A%98

// delete message 
// http://127.0.0.1:9503/api/channels.deleteMessages/?data[channel]=kursivalut_ru_donetsk&data[id][0]=10

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
    'url' => $options['url'] ?? $options['u'] ?? 'ws://127.0.0.1:9505/events',
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
                    
                    // сообщение не от моего профиля
                    isset($json->result->update->message->from_id->user_id) &&
                    "6354285876" != $json->result->update->message->from_id->user_id &&
                    "7500623149" != $json->result->update->message->from_id->user_id &&
                    
                    "updateNewChannelMessage" == $json->result->update->_) {
                    
                    // var_dump("6354285876" != $json->result->update->message->from_id->user_id);
                    var_dump("--- Other message ---");
                    $ad = $json->result->update;
                    $content = json_encode($ad);

//                    $url = getenv('APP_API_URL');
                    $url = env('APP_URL') . '/api/posts/add';

                    $curl_handle = curl_init();
                    curl_setopt($curl_handle, CURLOPT_URL, $url);
                    curl_setopt($curl_handle, CURLOPT_POST, 1);
                    curl_setopt($curl_handle, CURLOPT_POSTFIELDS, [
                        "password" => env('API_PASSWORD'),
                        "content" => $content,
                        "mygroup" => "1"]);
                    curl_setopt($curl_handle,CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl_handle, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
                    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($curl_handle, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
                    $query = curl_exec($curl_handle);
                    curl_close($curl_handle);
                    var_dump($query);
                    // var_dump($content);
                    printf("[%s] Received event: %s\n", date('Y-m-d H:i:s'), $payload);
                } else{
                    var_dump("--- My message ---");
                }
            }
        } catch (Throwable $e) {
            printf("Error: %s\n", $e->getMessage());
        }
        delay(0.1);

    }
});

$signal = Amp\trapSignal([SIGINT, SIGTERM]);
