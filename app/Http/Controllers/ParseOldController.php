<?php
 
namespace App\Http\Controllers;
use App\Models\Ads;
use App\Http\Controllers\DBController;
use App\Http\Controllers\PostAdsController;

class ParseOldController extends Controller
{
    /* Парсит направление, номер телефона и курс постов в БД */
    public static function parseOldAd( $ad ){
        $parser = (new self);
        $posts = new DBController;
        $phones_parsed = $parser->parsePhone( $ad["content"], $ad["vk_id"] );
            
        // распределение по направлениям купли/продажи и валюты
        $type = '';
        foreach( $vars->course_patterns as $key => $pattern ){
            $test_matches = preg_match($pattern, $phones_parsed["text"], $match);
            if( !empty($test_matches) ){
                if( empty($type) ){
                    $type = $key;
                } else{
                    $type = $type . ", " . $key;
                }
            }
        }

        $rate = 0;
        if($type){
            $rate = $parser->parseRate( $phones_parsed["text"], $type );
        }

        $args = [
            'content_changed' => $phones_parsed["text"],
            'phone'           => $phones_parsed["phones"],
            'rate'            => $rate
        ];
        $store = [
            "type" => "update",
            "compare" => [ 
                "key"   => 'vk_id', 
                "value" => $ad["vk_id"]
            ]
        ];
        $posts::storePosts( $args, $store );
    }
}