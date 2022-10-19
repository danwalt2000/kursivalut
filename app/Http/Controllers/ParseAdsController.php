<?php
 
namespace App\Http\Controllers;
use App\Models\Ads;
 
class ParseAdsController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public static function parsePhone ( $text, $id ){
        $result = $text;
        $pattern = "/[+0-9-]{10,20}/";
        preg_match_all( $pattern, $text, $matches );
        $index = 0;
        foreach($matches[0] as $phone ){
            $result = str_replace( $phone, '<a class="hidden_phone" onclick="getPhone([' . $id . ', ' . $index . '])">click</a>', $result );
            $index++;
        }
        return [ 
            "text"   => $result, 
            "phones" => implode(",", $matches[0])
        ];
    }

    public static function parseAd( $json, $group_id )
    {
        $currency = new CurrencyController;
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
        ];
        foreach( $ads as $ad ){
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

            $is_id_in_table = Ads::where('vk_id', '=', $ad["id"])->count();

            $is_text_in_table = Ads::where('content', '=', $ad["text"])->count();

            if( $is_text_in_table > 0 ){
                Ads::where('content', '=', $phones_parsed["text"])->update([
                    'vk_id'           => $ad["id"],
                    'owner_id'        => $ad["owner_id"],
                    'date'            => $ad["date"],
                    'link'            => $link
                ]);
            } elseif( $is_id_in_table < 1 && $ad["from_id"] != $ad["owner_id"] && !empty($ad["text"])){
                Ads::create([
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
                    'link'            => $link,
                    'type'            => $type
                ]);
            } 
        }
        
        return DBController::getPosts(); // последние 100 записей в БД
    }
}