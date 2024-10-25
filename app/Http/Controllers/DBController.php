<?php
 
namespace App\Http\Controllers;
use Log;
use Config;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Psr\Http\Message\RequestInterface;
 
class DBController extends Controller
{
    public static function getPosts( 
            $table = 'ads',
            $get_or_count = 'get', 
            $sell_buy = '', 
            $currency = '', 
            $search = '', 
            $offset = 0,
            $rate = 0.01,
            $date = ''
        ){
        $sort = 'date';
        $limit = 20;

        $rate_limit = $rate;
        // по умолчанию отображаем только объявления с курсом
        if( !empty($_GET["rate"]) && "false" == $_GET["rate"] ){
            $rate_limit = 0;
        }

        // проверяем, чтобы get-параметры строго соответствовали значениям,
        // иначе можно получить 500-ю ошибку
        if(!empty($_GET["sort"]) && in_array($_GET["sort"], ["date", "rate"]) ){
            $sort = $_GET["sort"];
        } 
        $asc_desc = 'desc';
        if(!empty($_GET["order"]) && in_array($_GET["order"], ["asc", "desc"]) ){
            $asc_desc = $_GET["order"];
        }
        
        // период, за который запрашиваются записи - измеряется в часах
        $time_range = 24;
        if( !empty($_GET["date"]) && 
            filter_var($_GET["date"], FILTER_VALIDATE_FLOAT) !== false){
            $time_range = $_GET["date"];
        }

        // Тип объявления, строится по принципу: (купля/продажа)_валюта
        // купля/продажа берется из пути sellbuy. Варианты: all, sell, buy
        // Валюта берется из пути currency
        $query = '';
        if( !empty($sell_buy) || !empty($currency) ){
            if($sell_buy != "all") $query = $sell_buy . '_'; 
            $query .= $currency;
        }

        // строка поиска
        $search_clean = '';
        if( !empty($search) ){
            $search_clean = preg_match('/[A-Za-zА-Яа-я0-9\.\,\s]/', $search) ? $search : "Вы ввели запрещенные символы";
        }
        $skip = $offset * $limit;
        $cut_by_time = time() - ($time_range * 60 * 60);

        return DB::table($table)
                ->where("date", ">", $cut_by_time)
                ->where('type', 'like', "%" . $query . "%")
                ->where('content_changed', 'like', "%" . $search_clean . "%")
                ->where('rate', '>=', $rate_limit)
                ->orderBy($sort, $asc_desc)
                ->skip($skip)
                ->take($limit)
                ->$get_or_count();
    }

    public static function getPostByContent( $content ){
        $table = SessionController::getHost()["table"];
        return DB::table($table)->where( 'content_changed', $content )->first();
    }

    // id может быть неуникальным, поскольку это id с площадки (vk, tg) 
    public static function getPostById( $id ){
        $table = SessionController::getHost()["table"];
        return DB::table($table)->where( 'vk_id', $id )->first();
    }
    
    // получение номера телефона: все найденные в объявлениях номера
    // телефонов скрываются, чтобы их не индексировали поисковики
    // получить номер телефона можно по клику на спойлер через xhr
    public static function getPhone( $info ){
        $ad = DBController::getPostById( $info["postId"] ); 
        
        // бывает, что пользователь находится на странице так долго, 
        // что за это время сервер успевает обновить объявление и по id его уже не найти. 
        // В таком случае ищем в БД объявление по содержанию
        if( empty($ad) ) $ad = DBController::getPostByContent( $info["content"] );
        if( empty($ad) ) return;

        // если телефонов несколько, отдаем по индексу
        $phones = explode(",", $ad->phone);
        return $phones[$info["phoneIndex"]];
    }

    public static function storePosts( $table, $args )
    {
        DB::table($table)->updateOrInsert( [ 'content' => $args['content'] ], $args );
    }

    // получает последний курс определенной валюты в определенной локали
    public static function getRate( $locale, $currency, $time ){
        return DB::table("rates")
                ->where( 'locale', $locale )
                ->where( 'currency', $currency )
                ->where( 'time', '<', $time + 59*60 )
                ->orderBy( 'time', 'desc' )
                ->first();
    }

    public static function getRatesByRange( $locale, $currency, $time_range ){
        return DB::table("rates")
                ->whereIn( 'locale', [$locale, 'stock'] )
                ->where( 'currency', $currency )
                ->where( 'time', '<', time() + 59*60 )
                ->where( 'time', '>', $time_range )
                ->orderBy( 'time', 'desc' )
                ->get(['average', 'time', 'locale']);
    }

    // получает средний курс из таблицы $table и направления $direction (например, продать доллар)
    // за время $time, отсекая курсы на 15% больше или меньше, чем имеющаяся в БД запись
    public static function getAvg( $table, $direction, $time,  $averages ){
        return DB::table($table)
               ->where( 'type', $direction )
               ->where('date', '<', $time )
               ->where('date', '>', $time - 24*60*60 )
               ->where('rate', '>', $averages[0])
               ->where('rate', '<', $averages[1])
               ->avg("rate");
    }

    public static function storeAvg( $args ){
        DB::table("rates")->updateOrInsert( [ 
            'time'      => $args['time'],
            'currency'  => $args['currency'],
            'locale'    => $args['locale'],
            'sell_rate' => $args['sell_rate'],
            'buy_rate'  => $args['buy_rate'],
        ], $args );
    }
}