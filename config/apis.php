<?php

use Illuminate\Support\Facades\Facade;

return [

    'keys' => [
        'vk' => [
            'url_key'        => 'https://api.vk.com/method/wall.get?v=5.81&access_token=',
            'items_key'      => 'items',
            'text_key'       => 'text',
            'channel_id_key' => 'owner_id',
            'url_channel_key'=> 'owner_id', 
            'url_limit_key'  => 'count', 
            'error_key'      => 'error'
        ],
        'tg' => [
            'url_key'        => 'http://127.0.0.1:9503/api/getHistory/?',
            'items_key'      => 'messages',
            'text_key'       => 'message',
            'channel_id_key' => 'peer_id', 
            'channel_sub'    => 'channel_id', // ключ дочернего объекта
            'user_sub'       => 'user_id',    // ключ дочернего объекта
            'url_channel_key'=> 'data[peer]', 
            'url_limit_key'  => 'data[limit]', 
            'error_key'      => 'errors'
        ]
    ]
];
