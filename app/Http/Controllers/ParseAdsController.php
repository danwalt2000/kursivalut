<?php
 
namespace App\Http\Controllers;
use Log;
use App\Models\Ads;
use App\Http\Controllers\DBController;
use App\Http\Controllers\PostAdsController;
use App\Http\Controllers\VarsController;

class ParseAdsController extends Controller
{
    public $vars;
    public $channel;
    public $domain;
    public $api_keys;
    
    /**
     * Распределяет объявления по направлениям и записывает в БД
     */
    public function parseAd( $json, $channel )
    {
        $posts = new DBController;
        $this->vars = new VarsController;
        $ads = $json;
        $this->channel = $channel;
        $this->domain = $channel['domain'];
        $this->api_keys = $this->vars->api_keys[ $this->domain ];
        $text_key = $this->api_keys['text_key'];
        
        foreach( $ads as $ad ){
            // если объявление уже есть в базе, пропускаем его
            $is_id_in_table = $posts->getPostById($ad["id"], "count"); //Ads::where('vk_id', '=', $ad["id"])->count();
            if( $is_id_in_table > 1 ) continue;               

            // извлечение номера телефона
            $phones_parsed = $this::parsePhone( $ad[$text_key], $ad["id"] );
            
            // распределение по направлениям купли/продажи и валюты
            $type = '';
            foreach( $this->vars->course_patterns as $key => $pattern ){
                $test_matches = preg_match($pattern, $phones_parsed["text"], $match);
                if( !empty($test_matches) ){
                    if( empty($type) ){
                        $type = $key;
                    } else{
                        $type = $type . ", " . $key;
                    }
                }
            }
            // объявления, у которых не получилось определить направление, 
            // считаются малоценными и в БД не записываются
            if( !$type ) continue; 
 
            $link = $this->createAdLink( $ad );

            $rate = 0;
            $rate = $this->parseRate( $phones_parsed["text"], $type );
            $is_text_in_table = Ads::where('content', '=', $ad[$text_key])->count();

            $user_id = $ad['from_id'];
            $owner_id = $ad[$this->api_keys['channel_id_key']];
            if( 'tg' == $this->domain ){ 
                $user_id = $user_id['user_id'];
                $owner_id = $owner_id['channel_id'];
            }

            if( $is_text_in_table > 0 ){
                $args = [
                    'vk_id'           => $ad["id"],
                    'owner_id'        => $owner_id,
                    'date'            => $ad["date"],
                    'content_changed' => $phones_parsed["text"],
                    'link'            => $link
                ];
                $store = [
                    "type" => "update",
                    "compare" => [ 
                        "key"   => 'content', 
                        "value" => $ad[$text_key]
                    ]
                ];
                
                $posts::storePosts( $args, $store );

            } elseif( $ad["from_id"] != $owner_id && !empty($ad[$text_key])){
                $args = [
                    'vk_id'           => $ad["id"],
                    'vk_user'         => $user_id,
                    'owner_id'        => $owner_id,
                    'date'            => $ad["date"],
                    'content'         => $ad[$text_key],
                    'content_changed' => $phones_parsed["text"],
                    'phone'           => $phones_parsed["phones"],
                    'rate'            => $rate,
                    'phone_showed'    => 0,
                    'link_followed'   => 0,
                    'popularity'      => 0,
                    'link'            => $link,
                    'type'            => $type
                ];
                
                $posts::storePosts( $args );
            } 
            // PostAdsController::postNewAds( $ad["id"] );
        }
        
        return $posts::getPosts(); // последние записи в БД
    }

    // извлекаем из объявления курс 
    public function parseRate ( $text, $types ){
        $types_arr = explode(",", $types);
        $rate = 0;
        $parser = (new self);

        foreach($types_arr as $type){         // типы объявлений, например, "sell_dollar, buy_hrn"
            $currency = explode("_" , $type); // извлекаем вторую часть
            $currency = $currency[1];         // например, из sell_hrn - hrn  

            // проверяем, есть ли в строке маска курса
            preg_match_all( $this->vars->rate_patterns[$currency], $text, $matches );

            if( isset($matches[0][0]) ){ // если есть
                
                // очищаем курс от лишних символов, 
                // например, из строки "о 72.2 М" извлекаем "72.2"
                preg_match_all( $this->vars->rate_digit_pattern, $matches[0][0], $match );
                $rate_string = str_replace(",", ".", $match[0][0]); // заменяем запятую на точку
                $rate = floatval($rate_string);

                if($currency == "cashless"){ 
                    $last_char = substr($matches[0][0], -1); // по безналу руб. - база 100
                    if($last_char == 1){
                        $rate = 100;                     // 100 = 1 к 1
                    } else{
                        $rate = 100 + floatval($rate_string);
                    }
                }
                break;  // если в объявлении несколько предложений и несколько курсов записываем только первый
            }
        }
        return $rate;
    }

    public static function parsePhone ( $text, $id ){
        $result = $text;
        $pattern = '/(071|072|949|095|050|066|\+38|79)([\d\-\s\)\(]{5,15})\d|(\+7)([\d\-\s\)\(]{8,15})\d/'; // "/[+0-9-]{10,20}/";
        preg_match_all( $pattern, $text, $matches );
        $index = 0;
        foreach($matches[0] as $phone ){
            $result = str_replace( $phone, '&#32;<button class="hidden_phone" title="Посмотреть номер" onclick="getPhone([' . $id . ', ' . $index . '])">click</button>&#32;', $result );
            $index++;
        }
        return [ 
            "text"   => $result, 
            "phones" => implode(",", $matches[0])
        ];
    }

    public function createAdLink ( $ad )
    {
        if( 'vk' == $this->domain ){
            $group = "club" . abs( intval( $ad["owner_id"] ) ); // id группы vk начинается с минуса
            $owner_and_id = $ad["owner_id"] . "_" . $ad["id"];
            $link = "https://vk.com/" . $group . "?w=wall" . $owner_and_id . "%2Fall";
        } else{
            $link = "https://t.me/" . $this->channel['id'] . "/" . $ad["id"];
        }
        return $link;
    }
}