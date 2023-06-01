<?php
 
namespace App\Http\Controllers;
use Log;
use Config;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\DBController;
 
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
        $show_rates = true;
        $rate_currencies = ['dollar', 'euro', 'hrn'];
        $table = 'stock';
        if( 'stock' != $locale ){
            $show_rates = $locale["show_rates"];
            $rate_currencies = $locale["rate_currencies"];
            $table = $locale["name"];
        }
        if( empty($show_rates) ) return;


        $rounded_time = $this->getRoundedTime( time() );
        $rates = [];
        foreach( $rate_currencies as $currency ){
            $rate = DBController::getRates($table, $currency, $rounded_time);
            if( !empty($rate) ){
                array_push($rates, $rate);
            }
        }
        
        return $rates;
    }

    public function writeRates( $time = null, $json = null )
    {
        $rounded_time = $this->getRoundedTime($time);
        
        // биржевые котировки
        if( !empty($json) ){
            $euro = $json["rates"]["RUB"];
            $currencies = [
                "euro"   => $euro,
                "dollar" => $euro / $json["rates"]["USD"],
                "hrn"    => $euro / $json["rates"]["UAH"]
            ];
            foreach($currencies as $currency => $rate){
                $args = [
                    'time'       => $rounded_time,
                    'currency'   => $currency,
                    'symbol'     => Config::get('common.symbols')[$currency],
                    'sell_rate'  => round($rate, 2),
                    'buy_rate'   => round($rate, 2),
                    'average'    => round($rate, 2),
                    'changes'    => $this->getDayBeforeAverage('stock', $currency, $rounded_time, $rate),
                    'locale'     => 'stock'
                ];
                DBController::storeAvg( $args );
            }
            return;
        }

        foreach( $this->locales as $locale ){
            // в некоторых локалях слишком мало объявлений
            // для стабильного вычисления среднего курса и построения графика
            if( empty($locale["show_rates"]) ) continue; 

            foreach( $locale["rate_currencies"] as $currency ){
                $table = $locale["name"];
                $avgs = [0, 999];
                // предыдущий курс 
                $db_rates = DBController::getRates($table, $currency, $rounded_time);
                if( !empty($db_rates) ){
                    $last_average = $db_rates->average; 
                    // отсеккаем значения на 15% меньше и больше предыдущего курса 
                    $avgs = [ $last_average * 0.85, $last_average * 1.15 ]; 
                }
                $avg_sell = DBController::getAvg($table, "sell_" . $currency, $rounded_time, $avgs );
                $avg_buy = DBController::getAvg($table, "buy_" . $currency, $rounded_time, $avgs );

                // если в таблице слишком мало значений для вычисления среднего курса,
                // берем предыдущий курс
                if( empty($avg_sell)  && !empty($db_rates->sell_rate) ) $avg_sell = $db_rates->sell_rate;
                if( empty($avg_buy) && !empty($db_rates->buy_rate) ) $avg_buy = $db_rates->buy_rate;
                $average = ($avg_sell + $avg_buy) / 2;

                $changes = $this->getDayBeforeAverage($table, $currency, $rounded_time, $average);

                if( !empty($avg_sell) && !empty($avg_buy) ) {
                    $args = [
                        'time'       => $rounded_time,
                        'currency'   => $currency,
                        'symbol'     => Config::get('common.symbols')[$currency],
                        'sell_rate'  => round($avg_sell, 2),
                        'buy_rate'   => round($avg_buy, 2),
                        'average'    => round($average, 2),
                        'changes'    => $changes,
                        'locale'     => $table
                    ];
                    DBController::storeAvg( $args );
                }
            }
        }
       
    }

    public function getStockRates(){
        $api_url = "http://api.exchangeratesapi.io/v1/latest?format=1&symbols=USD,RUB,UAH&access_key=";
        $access_key = env('STOCK_API_KEY');
        $api_url .= $access_key;

        try {
            $response = Http::get($api_url);
        } catch(\Exception $exception) {
            return Log::error($exception);
        }
        $json = json_decode($response->getBody(), true);

        if( !empty($json["success"]) ){
            return $this->writeRates($json["timestamp"], $json);
        }
    }

    // получает текущее либо заданное время, округленное до часа
    public function getRoundedTime( $time )
    {
        $seconds = $time ? $time : time();
        return round($seconds / (60 * 60)) * (60 * 60);
    }
    
    // получает курс на конец предыдущего дня, чтобы вычислить динамику
    public function getDayBeforeAverage( $table, $currency, $rounded_time, $average )
    {
        $changes = 0;
        $day_before = strtotime("yesterday", $rounded_time);
        $day_before_average = DBController::getRates($table, $currency, $day_before);
        if( !empty($day_before_average) ){
            $changes = $average - $day_before_average->average;
        }
        return $changes;
    }

}