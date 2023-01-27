<?php
 
namespace App\Http\Controllers;
use App\Models\Ads;
use App\Http\Controllers\DBController;
use App\Http\Controllers\PostAdsController;

class ParseAdsController extends Controller
{
    public $course_patterns = [
        "sell_dollar"      => '/(Прод|прод|ПРОД|[бо]мен[яи])(.*)(\$|Дол|ДОЛ|дол|бел[ыо][йг]|син|зел|💵)/', // старая маска [Пп]род.*(\$|дол|син|зел|💵)(.*?\d{2})
        "sell_euro"        => '/(Прод|прод|ПРОД|[бо]мен[яи])(.*)(\€|евро|Евро|ЕВРО)/',
        "sell_hrn"         => '/(Прод|прод|ПРОД|[бо]мен[яи]|Пополн|пополн)(.*)(Грив|грив|ГРИВ|Грн|ГРН|грн|\sгр\s|\sгр\.|укр|Укр|Приват|приват|ПРИВАТ|Ощад|ощад|ОЩАД|Моно|моно)/',
        "sell_cashless"    => '/(Прод|прод|ПРОД|[бо]мен[яи]|Пополн|пополн)(.*)(Сбер|сбер|СБЕР|[Тт]инько)/',
        
        "buy_dollar"       => '/(Куп|куп|КУП)(.*)(\$|Дол|ДОЛ|дол|бел[ыо][йг]|син|зел|💵)/',
        "buy_euro"         => '/(Куп|куп|КУП)(.*)(\€|евро|Евро|ЕВРО)/',
        "buy_hrn"          => '/(Куп|куп|КУП|Обналич|обналич)(.*)(Грив|грив|ГРИВ|Грн|ГРН|грн|\sгр\s|\sгр\.|укр|Укр|Приват|приват|ПРИВАТ|Ощад|ощад|ОЩАД|Моно|моно)/',
        "buy_cashless"     => '/(Куп|куп|КУП|Обналич|обналич)(.*)(Сбер|сбер|СБЕР|[Тт]инько)/'
    ];
    public $rate_patterns = [
        // маска захватывает символы до и после курса, чтобы убедиться, что мы не попали на часть номера телефона, суммы или других чисел
        "dollar"      => '/\D{2}[678](\d$|\d\D{2}|\d([\.\,]\d?)\d$|\d([\.\,]\d*)\D{2})|[678][0-9]([\.\,]\d{0,2})?-[678][0-9]([\.\,]\d{0,2})?/',
        // пока евро одинаковый с долларом
        "euro"        => '/\D{2}[678](\d$|\d\D{2}|\d([\.\,]\d?)\d$|\d([\.\,]\d*)\D{2})|[678][0-9]([\.\,]\d{0,2})?-[678][0-9]([\.\,]\d{0,2})?/', 
        "hrn"         => '/(\D[-\s\(\)][12]([\.\,]\d{0,2})(\d$|\D\D))|[12]([\.\,]\d{1,2})?\s?-\s?[12]([\.\,]\d{0,2})?/',
        "cashless"    => '/(1[\s]?[к\:х\*\/][\s]?1)|(\d+[\.\,])?\d+\s?\%/'
    ];

    public $rate_digit_pattern = '/\d*[\.\,]?\d+/';

    public static function parseAd( $json, $group_id )
    {
        $posts = new DBController;
        $parser = (new self);
        $ads = $json;
        
        foreach( $ads as $ad ){
            $is_id_in_table = $posts->getPostById($ad["id"], "count"); //Ads::where('vk_id', '=', $ad["id"])->count();
            if( $is_id_in_table > 1 ){
                continue;               // если объявление уже есть в базе, пропускаем его
            }

            // вырезание номера телефона
            $phones_parsed = $parser->parsePhone( $ad["text"], $ad["id"] );
            
            $group = "club" . abs( intval( $group_id ) ); // id группы vk начинается с минуса
            $owner_and_id = $ad["owner_id"] . "_" . $ad["id"];
            $link = "https://vk.com/" . $group . "?w=wall" . $owner_and_id . "%2Fall";
            
            // распределение по направлениям купли/продажи и валюты
            $type = '';
            foreach( $parser->course_patterns as $key => $pattern ){
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

            $is_text_in_table = Ads::where('content', '=', $ad["text"])->count();

            if( $is_text_in_table > 0 ){
                $args = [
                    'vk_id'           => $ad["id"],
                    'owner_id'        => $ad["owner_id"],
                    'date'            => $ad["date"],
                    'content_changed' => $phones_parsed["text"],
                    'link'            => $link
                ];
                $store = [
                    "type" => "update",
                    "compare" => [ 
                        "key"   => 'content', 
                        "value" => $ad["text"]
                    ]
                ];
                $posts::storePosts( $args, $store );

            } elseif( $ad["from_id"] != $ad["owner_id"] && !empty($ad["text"])){
                $args = [
                    'vk_id'           => $ad["id"],
                    'vk_user'         => $ad["from_id"],
                    'owner_id'        => $ad["owner_id"],
                    'date'            => $ad["date"],
                    'content'         => $ad["text"],
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
    public static function parseRate ( $text, $types ){
        $types_arr = explode(",", $types);
        $rate = 0;
        $parser = (new self);

        foreach($types_arr as $type){         // типы объявлений, например, "sell_dollar, buy_hrn"
            $currency = explode("_" , $type); // извлекаем вторую часть
            $currency = $currency[1];         // например, из sell_hrn - hrn  

            // проверяем, есть ли в строке маска курса
            preg_match_all( $parser->rate_patterns[$currency], $text, $matches );

            if( isset($matches[0][0]) ){ // если есть
                
                // очищаем курс от лишних символов, 
                // например, из строки "о 72.2 М" извлекаем "72.2"
                preg_match_all( $parser->rate_digit_pattern, $matches[0][0], $match );
                $rate_string = str_replace(",", ".", $match[0][0]); // заменяем запятую на точку
                $rate = floatval($rate_string);

                if($currency == "cashless"){ 
                    $last_char = substr($matches[0][0], -1); // по безналу руб. - база 100
                    // var_dump($matches[0][0]);
                    // echo "<br>";
                    if($last_char == 1){
                        $rate = 100;                     // 100 = 1 к 1
                    } else{
                        $rate = 100 + floatval($rate_string);
                    }
                    // var_dump($rate);
                    // echo "<br>";
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

    /* Парсит направление, номер телефона и курс постов в БД */
    public static function parseOldAd( $ad ){
        $parser = (new self);
        $posts = new DBController;
        $phones_parsed = $parser->parsePhone( $ad["content"], $ad["vk_id"] );
            
        // распределение по направлениям купли/продажи и валюты
        $type = '';
        foreach( $parser->course_patterns as $key => $pattern ){
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