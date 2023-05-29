<?php
 
namespace App\Http\Controllers;
use Log;
use Config;
use App\Http\Controllers\DBController;
use App\Http\Controllers\CurrencyController;
 
class RatesController extends Controller
{
    public function writeRates( $time = null ){
        $rounded_time = $this->getRoundedTime($time);
        $locales =  Config::get('locales'); 
        // var_dump(date("m.d H:i", $rounded_time));
        // var_dump(date("m.d H:i", $rounded_time - 24*60*60 ));
        // var_dump($rounded_time);
        // var_dump($rounded_time - 24*60*60 );
        foreach( $locales as $locale ){
            if( empty($locale["show_rates"]) ) continue;
            foreach( $locale["rate_currencies"] as $currency ){
                $table = $locale["name"];
                $avgs = [0, 999];
                $db_rates = DBController::getRates($table, $currency, $rounded_time);
                if( !empty($db_rates) ){
                    $sell_rate = $db_rates->sell_rate;
                    $buy_rate = $db_rates->buy_rate;
                    $avgs = [ $buy_rate * 0.85, $sell_rate * 1.15 ];
                    var_dump($sell_rate);
                }
                $avg_sell = DBController::getAvg($table, "sell_" . $currency, $rounded_time, $avgs );
                $avg_buy = DBController::getAvg($table, "buy_" . $currency, $rounded_time, $avgs );
                // echo $locale["name"] . " " . $currency . " sell " . var_dump($avg_sell) . "<br>";
                // echo $locale["name"] . " " . $currency . " buy " . var_dump($avg_buy) . "<br>";
                if( !empty($avg_sell) && !empty($avg_buy) ) {
                    $args = [
                        'time'       => $rounded_time,
                        'currency'   => $currency,
                        'sell_rate'  => round($avg_sell, 2),
                        'buy_rate'   => round($avg_buy, 2),
                        'locale'     => $table
                    ];
                    DBController::storeAvg( $args );

                }

            }
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
    // public function getAverage( $locale, $time, $direction ){
        // return ($locale, $time, $direction);
        // $input = $request->all();
        // $ad = DBController::getPhone($input);
        // if( empty($ad->phone) ) return $ad; 
        // return $ad->phone;
    // }
        
    // получает текущее либо заданное время, округленное до часа
    public function getRoundedTime( $time ){
        $seconds = $time ? $time : time();
        return round($seconds / (60 * 60)) * (60 * 60);
    }

}