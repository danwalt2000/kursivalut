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
            "sell_dollar"      => '/(Прод|прод|ПРОД|[бо]меня)(.*)(\$|Дол|ДОЛ|дол|бел[ыо][йг]|син|зел|💵)/', // старая маска [Пп]род.*(\$|дол|син|зел|💵)(.*?\d{2})
            "sell_euro"        => '/(Прод|прод|ПРОД|[бо]меня)(.*)(\€|евро|Евро|ЕВРО)/',
            "sell_hrn"         => '/(Прод|прод|ПРОД|[бо]меня|Пополн|пополн)(.*)(Грив|грив|ГРИВ|Грн|ГРН|грн|\sгр\s|укр|Укр|Приват|приват|ПРИВАТ|Ощад|ощад|ОЩАД|Моно|моно)/',
            "sell_cashless"    => '/(Прод|прод|ПРОД|[бо]меня|Пополн|пополн)(.*)(Сбер|сбер|СБЕР|[Тт]инько)/',
            
            "buy_dollar"       => '/(Куп|куп|КУП)(.*)(\$|Дол|ДОЛ|дол|бел[ыо][йг]|син|зел|💵)/',
            "buy_euro"         => '/(Куп|куп|КУП)(.*)(\€|евро|Евро|ЕВРО)/',
            "buy_hrn"          => '/(Куп|куп|КУП)(.*)(Грив|грив|ГРИВ|Грн|ГРН|грн|\sгр\s|укр|Укр|Приват|приват|ПРИВАТ|Ощад|ощад|ОЩАД|Моно|моно)/',
            "buy_cashless"     => '/(Куп|куп|КУП)(.*)(Сбер|сбер|СБЕР|[Тт]инько)/'
            // "course" => "/(по|курс) ([\d\.\,]{2,5}) /"
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
        $pattern = "/[+0-9-]{10,20}/"; // (?<=[0-9\+])[0-9 )(+-]+   ([0-9\+][0-9 )(+-]+?(?=\w)){10,20}
        preg_match_all( $pattern, $text, $matches );
        $index = 0;
        foreach($matches[0] as $phone ){
            $result = str_replace( $phone, '<button class="hidden_phone" onclick="getPhone([' . $id . ', ' . $index . '])">click</button>', $result );
            $index++;
        }
        return [ 
            "text"   => $result, 
            "phones" => implode(",", $matches[0])
        ];
    }
}