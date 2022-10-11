<?php
 
namespace App\Http\Controllers;
// use GuzzleHttp\Middleware;
// use Illuminate\Support\Facades\Http;
// use Psr\Http\Message\RequestInterface;
// use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
// use Log;
 
class ParseAdsController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public $db_ads = [];

    public static function parseAd( $json )
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
            // "phone_number" => "/[+0-9-]{10,20}/"
            // "course" => "/(по|курс) ([\d\.\,]{2,5}) /"
        ];
        foreach( $ads as $ad ){
            $text = $ad["text"];
            // $this->last_ad_time = DB::table('ads')->orderBy("date", "desc")->first();
            
            $group = "club" . abs( intval( $currency->publics["obmenvalut_donetsk"] ) );
            $owner_and_id = $ad["owner_id"] . "_" . $ad["id"];
            $link = "https://vk.com/" . $group . "?w=wall" . $owner_and_id . "%2Fall";
            
            $type = '';
            foreach( $patterns as $key => $pattern ){
                $test_matches = preg_match($pattern, $text, $match);
                if( !empty($test_matches) ){
                    if( empty($type) ){
                        $type = $key;
                    } else{
                        $type = $type . ", " . $key;
                    }
                }
            }

            $is_id_in_table = DB::table('ads')
                                ->where('vk_id', '=', $ad["id"])
                                ->where('owner_id', '=', $ad["owner_id"])
                                ->get();

            $is_text_in_table = DB::table('ads')->where('text', '=', $ad["text"])->get();

            if( count($is_text_in_table) ){
                DB::table('ads') ->where('text', '=', $ad["text"])->update([
                    'vk_id'      => $ad["id"],
                    'date'       => $ad["date"],
                    'link'       => $link
                ]);
            } elseif( !count($is_id_in_table) && $ad["from_id"] != $ad["owner_id"] ){
                DB::table('ads')->insert([
                    'vk_id'      => $ad["id"],
                    'vk_user'    => $ad["from_id"],
                    'owner_id'   => $ad["owner_id"],
                    'date'       => $ad["date"],
                    'text'       => $ad["text"],
                    'link'       => $link,
                    'type'       => $type
                ]);
            } 
        }
        
        return DB::table('ads')->limit('100')->orderBy("date", "desc")->get();
    }
}