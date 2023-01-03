<?php
 
namespace App\Http\Controllers;
use App\Models\Ads;
use App\Http\Controllers\DBController;

class ParseAdsController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public static function parseAd( $json, $group_id )
    {
        $posts = new DBController;
        $ads = $json;
        $patterns = [
            "sell_dollar"      => "/[Пп]род.*(\$|дол|син|зел|💵)(.*?\d{2})/",
            "sell_euro"        => "/[Пп]род.*(\€|евро)(.*?\d{2})/",
            "sell_hrn"         => "/[Пп]род.*([Гг]рн|грив|[Пп]риват|[Оо]щад|[Мм]оно)/",
            "sell_cashless"    => "/[Пп]род.*([Cс]бер|[Тт]иньк)/",
            
            "buy_dollar"       => "/[Кк]уп.*(\$|дол|син|зел|💵)(.*?\d{2})/",
            "buy_euro"         => "/[Кк]уп.*(\€|евро)(.*?\d{2})/",
            "buy_hrn"          => "/[Кк]уп.*([Гг]рн|грив|[Пп]риват|[Оо]щад|[Мм]оно)/",
            "buy_cashless"     => "/[Кк]уп.*([Cс]бер|[Тт]иньк)/"
            // "course" => "/(по|курс) ([\d\.\,]{2,5}) /"
            // "dollar_course" => "/\s[6789][0-9]([\.\,]\d{0,2})?([\s\.\,])/"
        ];
        foreach( $ads as $ad ){
            $is_id_in_table = Ads::where('vk_id', '=', $ad["id"])->count();
            if( $is_id_in_table > 1 ){
                continue;               // если объявление уже есть в базе, пропускаем его
            }

            // вырезание номера телефона
            $phones_parsed = (new self)->parsePhone( $ad["text"], $ad["id"] );
            
            $group = "club" . abs( intval( $group_id ) );
            $owner_and_id = $ad["owner_id"] . "_" . $ad["id"];
            $link = "https://vk.com/" . $group . "?w=wall" . $owner_and_id . "%2Fall";
            
            // распределение по направлениям купли/продажи и валюты
            $type = '';
            foreach( $patterns as $key => $pattern ){
                $test_matches = preg_match($pattern, $phones_parsed["text"], $match);
                if( !empty($test_matches) ){
                    if( empty($type) ){
                        $type = $key;
                    } else{
                        $type = $type . ", " . $key;
                    }
                }
            }

            $is_text_in_table = Ads::where('content', '=', $ad["text"])->count();

            if( $is_text_in_table > 0 ){
                $args = [
                    'vk_id'           => $ad["id"],
                    'owner_id'        => $ad["owner_id"],
                    'date'            => $ad["date"],
                    // 'content_changed' => $phones_parsed["text"],
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
                    'rate'            => 0,
                    'phone_showed'    => 0,
                    'link_followed'   => 0,
                    'popularity'      => 0,
                    'link'            => $link,
                    'type'            => $type
                ];
                $posts::storePosts( $args );
            } 
        }
        
        return $posts::getPosts(); // последние записи в БД
    }

    public static function parsePhone ( $text, $id ){
        $result = $text;
        $pattern = "/\s([+\d\- ]{10,20})[\s\.\,]?/"; // "/[+0-9-]{10,20}/"; // (?<=[0-9\+])[0-9 )(+-]+   ([0-9\+][0-9 )(+-]+?(?=\w)){10,20}
        preg_match_all( $pattern, $text, $matches );
        $index = 0;
        foreach($matches[0] as $phone ){
            $result = str_replace( $phone, '&#32;<button class="hidden_phone" onclick="getPhone([' . $id . ', ' . $index . '])">click</button>&#32;', $result );
            $index++;
        }
        return [ 
            "text"   => $result, 
            "phones" => implode(",", $matches[0])
        ];
    }
}