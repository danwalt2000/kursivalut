<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;
use App\Http\Controllers\CurrencyController;
 
class ParseUriController extends Controller
{
    public static function getH1(){
        $path = ParseUriController::parseUri();
        // с падежами
        $currencies_loc = [
            "dollar" => "доллара",
            "euro" => "евро",
            "hrn" => "гривны (Ощад, Приват, Моно)",
            "cashless" => "безнала руб. (Сбер, Тиньков)"
         ];
        $result = "";
        if( $path['sell_buy'] == 'sell'){
            $result .= "Продажа ";
        } elseif( $path['sell_buy'] == 'buy'){
            $result .= "Покупка ";
        } else{
            $result .= "Обмен ";
        }
        foreach($currencies_loc as $name => $title){
            if($path["currency"] == ''){
                $result .= "валюты";
                break;
            }
            if($path["currency"] == $name ){
                $result .= $title;
            }
        }
        return $result . " в Донецке";
    }

    public static function parseUri(){
        $url = explode( "?", \Request::getRequestUri() );
        $path = $url[0];
        $query = '';
        $hours = 24;
        $sort = "date_desc";
        $rate = "true";
        $message = 'Показаны только объявления, содержащие курс. Чтобы посмотреть все предложения, снимите галочку "Только с курсом".';
        $hint = $message;

        if( !empty($url[1]) ){
            $query = $url[1];
            $hours_pattern = "/(?<=(date\=))[\d+.-]+/";
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
            "sort"     => $sort,      // тип сортировки для подсветки активных чипсов
            "rate"     => $rate,
            "hint"     => $hint
        ];
        if( str_contains($path, "ads") ){
            $path_array = explode("/", $path);
            $path_parts["sell_buy"] =  $path_array[2];
            $path_parts["currency"] = empty($path_array[3]) ? '' : $path_array[3];
            
            // на странице валют по умолчанию выводим сообщение о курсе
            if( !empty($path_parts["currency"]) && empty($_GET["rate"]) ) $path_parts["hint"] = $message; 
        }
        return $path_parts;
    }
}