<?php
 
namespace App\Http\Controllers;
use Log;
use Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\RequestInterface;
use App\Http\Controllers\CurrencyController;
// use App\Models\{Ads, Donetsk, Lugansk, Mariupol};
 
class DBController extends Controller
{
    public static function getPosts( 
            $table = 'ads',
            $get_or_count = 'get', 
            $sell_buy = '', 
            $currency = '', 
            $search = '', 
            $offset = 0,
            $rate = 0.01
        ){
        $sort = 'date';
        $limit = 20;

        $rate_limit = $rate;
        // по умолчанию отображаем только объявления с курсом
        if( !empty($_GET["rate"]) && "false" == $_GET["rate"] ){
            $rate_limit = 0;
        }

        if(!empty($_GET["sort"]) && str_contains( "date rate popularity", $_GET["sort"]) ){
            $sort = $_GET["sort"];
        } 
        $asc_desc = 'desc';
        if(!empty($_GET["order"]) && str_contains( "asc desc", $_GET["order"]) ){
            $asc_desc = $_GET["order"];
        }
        
        // период, за который запрашиваются записи - измеряется в часах
        $time_range = 24;
        if(!empty($_GET["date"]) && 
            filter_var($_GET["date"], FILTER_VALIDATE_INT)!== false &&
            (24 == $_GET["date"] || 5 == $_GET["date"] || 168 == $_GET["date"]) ){
            $time_range = $_GET["date"];
        }

        // Тип объявления, строится по принципу: (купля/продажа)_валюта
        // купля/продажа берется из пути sellbuy. Варианты: all, sell, buy
        // Валюта берется из пути currency
        $query = '';
        if( !empty($sell_buy) || !empty($currency) ){
            if($sell_buy != "all"){
                $query = $sell_buy . '_';
            }
            $query .= $currency;
        }

        // строка поиска
        $search_clean = '';
        if( !empty($search) ){
            $search_clean = htmlspecialchars($search);
        }
        $skip = $offset * $limit;

        $cut_by_time = time() - $time_range * 60 * 60;
        
        return DB::table($table)
                ->where("date", ">", $cut_by_time)
                ->where('type', 'like', "%" . $query . "%")
                ->where('content', 'like', "%" . $search_clean . "%")
                ->where('rate', '>=', $rate_limit)
                ->orderBy($sort, $asc_desc)
                ->skip($skip)
                ->take($limit)
                ->$get_or_count();
    }

    // public function getPostByContent( $table, $content ){
    //     return DB::table($table)->where( 'content', $content )->count();
    // }

    public static function getPostById( $id ){
        $table = Config::get('locales.table');
        return DB::table($table)->where( 'vk_id', $id )->first();
        // return $model::where('vk_id', '=', $id)->take(1)->$get_or_count();
    }
    
    public static function getPhone( $info ){
        $ad = (new self)->getPostById($info["postId"]); // Ads::where('vk_id', $info["postId"])->take(1)->get();
        if( !count($ad) ){
            return;
        }
        $ad = $ad[0]; 

        // добавляем +1 к показам телефона или открытию ссылки
        $phone_or_link = "phone_showed";
        if( $info["phoneOrLink"] == "link" ){
            $phone_or_link = "link_followed";
        }
        $actual_value = $ad->{$phone_or_link};
        if( empty($actual_value) ) $actual_value = 0; 

        // добавляем +1 к популярности при каждом просмотре номера 
        $popularity = $ad->popularity; 
        if( empty($popularity) ) $popularity = 0; 
        $ad->update([
            'popularity'     => $popularity + 1,
            $phone_or_link   => $actual_value + 1
        ]); 

        // если телефонов несколько, отдаем по индексу
        $phones = explode(",", $ad->phone);
        return $phones[$info["phoneIndex"]];
    }

    public static function storePosts( $table, $args )
    {
        // if( !empty($store["type"]) && $store["type"] == "update" ){
        //     DB::table($table)::where($store["compare"]["key"], '=', $store["compare"]["value"])
        //         ->update($args);
        // } else{
        //     // Log::error($args);
        //     $model::create($args);
        // }
        DB::table($table)->upsert(
            $args,
            ['content'],
            $args
        );
        // DB::table($table)::where($store["compare"]["key"], '=', $store["compare"]["value"])
        //         ->update($args);
    }
}