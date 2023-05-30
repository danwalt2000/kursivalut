<?php
 
namespace App\Http\Controllers;
use Log;
use Config;
use Illuminate\Http\Request;
use App\Http\Controllers\DBController;
use App\Http\Controllers\CurrencyController;
 
class RatesController extends Controller
{
    public $locales = [];

    public function __construct()
    {
        $this->locales =  Config::get('locales'); 
    }

    public function getAll()
    {
        $rounded_time = $this->getRoundedTime( time() );
        $rates = (object) [ 
            'rates' => []
        ];
        foreach( $this->locales as $locale ){
            if( empty($locale["show_rates"]) ) continue; 
            foreach( $locale["rate_currencies"] as $currency ){
                $rate = DBController::getRates($locale["name"], $currency, $rounded_time);
                if( !empty($rate) ) array_push($rates->rates, $rate);
            }
        }
        return $rates;
    }

    public function getRatesByLocale( $locale )
    {
        if( empty($locale["show_rates"]) ) return;

        $rounded_time = $this->getRoundedTime( time() );
        $rates = [];
        foreach( $locale["rate_currencies"] as $currency ){
            $rate = DBController::getRates($locale["name"], $currency, $rounded_time);
            if( !empty($rate) ){
                $rate->avg = round( ($rate->sell_rate + $rate->buy_rate)/2, 2); 
                $rate->name = Config::get('common.currencies_rates')[$rate->currency]; 
                array_push($rates, $rate);
            }
        }
        
        return $rates;
    }

    public function writeRates( $time = null )
    {
        $rounded_time = $this->getRoundedTime($time);

        foreach( $this->locales as $locale ){
            // в некоторых локалях слишком мало объявлений
            // для стабильного вычисления среднего курса и построения графика
            if( empty($locale["show_rates"]) ) continue; 

            foreach( $locale["rate_currencies"] as $currency ){
                $table = $locale["name"];
                $avgs = [0, 999];
                $db_rates = DBController::getRates($table, $currency, $rounded_time);
                if( !empty($db_rates) ){
                    $sell_rate = $db_rates->sell_rate;
                    $buy_rate = $db_rates->buy_rate;
                    $avgs = [ $buy_rate * 0.85, $sell_rate * 1.15 ];
                }
                $avg_sell = DBController::getAvg($table, "sell_" . $currency, $rounded_time, $avgs );
                $avg_buy = DBController::getAvg($table, "buy_" . $currency, $rounded_time, $avgs );
                if( empty($avg_sell) ) $avg_sell = $db_rates->sell_rate;
                if( empty($avg_buy) ) $avg_buy = $db_rates->buy_rate;

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
        }
       
    }

    // получает текущее либо заданное время, округленное до часа
    public function getRoundedTime( $time )
    {
        $seconds = $time ? $time : time();
        return round($seconds / (60 * 60)) * (60 * 60);
    }

}