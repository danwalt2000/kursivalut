<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;
use Config;
 
class ParseUriController extends Controller
{
    public static function getH1(){
        $host = SessionController::getHost();
        $locale = Config::get('locales.' . $host['table']);
        
        $title = ParseUriController::generateTitle()->h1;
        
        return $title->sell_buy . $title->currency . $locale['h1_keyword'];
    }

    public static function generateTitle( $uri = null ){
        $host = SessionController::getHost();
        $locale = Config::get('locales.' . $host['table']);

        $path = $uri ?? ParseUriController::parseUri();
        $headline = (object)[ 
            "h1" => (object)[
                "locale" => $locale['h1_keyword']
            ],
            "description" => (object)[
                "rate" => "только объявления содержащие курс",
                "hint" => 'Чтобы посмотреть все предложения, снимите в фильтрах галочку "Только с курсом".',
                "sort" => 'убывания'
            ]
        ];
        if( $path['sell_buy'] == 'sell'){
            $headline->h1->sell_buy = "Продажа ";
        } elseif( $path['sell_buy'] == 'buy'){
            $headline->h1->sell_buy = "Покупка ";
        } else{
            $headline->h1->sell_buy = "Обмен ";
        }
        foreach(Config::get('common.currencies_loc') as $name => $title){
            if($path["currency"] == ''){
                $headline->h1->currency = "валюты";
                break;
            }
            if($path["currency"] == $name ){
                $headline->h1->currency = $title;
            }
        }
        $headline->description->hours = " на сегодня";
        if( !empty($path['hours']) ){
            if($path['hours'] == 5){
                $headline->description->hours = " за последние пять часов";
            } elseif($path['hours'] == 168){
                $headline->description->hours = " за последнюю неделю";
            }
        }
        if( !empty($_GET["rate"]) && $_GET["rate"] == "false" ){
            $headline->description->rate = "все объявления";
            $headline->description->hint = "";
        } 
        if( !empty($_GET["order"]) && $_GET["order"] == "asc" ) $headline->description->sort = "возрастания"; 

        return $headline;
    }

    public static function parseUri(){
        $url = explode( "?", \Request::getRequestUri() );
        $path = $url[0];
        $query = '';
        $hours = 24;
        $sort = "date_desc";
        $rate = "true";

        if( !empty($url[1]) ){
            $query = $url[1];
            $hours_pattern = "/(?<=(date\=))(5|24|168)/";
            $sort_type_pattern = "/((?<=(sort\=))[\w+.]+)/";
            $order_pattern = "/((?<=(order\=))[\w+.]+)/";
            $rate_pattern = "/((?<=(rate\=))[\w+.]+)/";

            preg_match($hours_pattern, $url[1], $hours_matches);
            preg_match($sort_type_pattern, $url[1], $sort_matches);
            preg_match($order_pattern, $url[1], $order_matches);
            preg_match($rate_pattern, $url[1], $rate_matches);
            if(!empty($hours_matches[0])) $hours = $hours_matches[0];
            if(!empty($sort_matches[0]) && !empty($order_matches[0])) $sort = $sort_matches[0] . "_" . $order_matches[0];
            if(!empty($rate_matches[0])){
                $rate = $rate_matches[0];
                if( !empty($_GET["rate"]) && "false" == $rate ) $hint = ""; 
            } 
        }
        $path_parts = [ 
            "sell_buy" => "all", 
            "currency" => "", 
            "query"    => $query,    // строка get-параметров
            "hours"    => $hours,    // количество часов для фильтрации
            "sort"     => $sort,     // тип сортировки для подсветки активных чипсов
            "rate"     => $rate,
        ];
        if( str_contains($path, "ads") ){
            $path_array = explode("/", $path);
            $path_parts["sell_buy"] =  $path_array[2];
            $path_parts["currency"] = $path_array[3] ?? '';
            
        }
        $message = ParseUriController::generateTitle($path_parts)->description;
        $path_parts["desc"] = "Показаны " . $message->rate . $message->hours . " в порядке " . $message->sort . " даты публикации.";
        // $path_parts["hint"] = $message["hint"];

        return $path_parts;
    }
}