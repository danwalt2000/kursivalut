<?php
 
namespace App\Http\Controllers;
use Log;
use Config;
use App\Http\Controllers\DBController;
use App\Http\Controllers\CurrencyController;
 
class RatesController extends Controller
{
    public function writeRates( $time = null ){
        $seconds = $time ? $time : time();
        $rounded_time = round($seconds / (60 * 60)) * (60 * 60);
        $locales =  Config::get('locales'); 
        foreach( $locales as $locale ){
            // $this->currencies[$currency] = Config::get('common.currencies')[$currency];
        }
        // $to_view = (new CurrencyController)->to_view;
        // $table = SessionController::getHost()["table"];

        // $sellbuy = $request->query('sellbuy');
        // if( empty($sellbuy) ) $sellbuy = '';

        // $currency = $request->query('currency');
        // if( empty($currency) ) $currency = '';
        
        // $offset = $request->query('offset');
        // if( empty($offset) ) $offset = 0;
        
        // $search = $request->query('search');
        // if( empty($search) ) $search = '';

        // $to_view['ads'] = DBController::getPosts( $table, "get", $sellbuy, $currency, $search, $offset );
        // return view('parts.feed', $to_view);;
    }

    // получает среднее значение курса за определенное время $time
    // $locale - город и таблица, например, donetsk
    // $direction - направление, например, продажа доллара - sell_dollar
    public function getAverage( $locale, $time, $direction ){
        // $input = $request->all();
        // $ad = DBController::getPhone($input);
        // if( empty($ad->phone) ) return $ad; 
        // return $ad->phone;
    }
}