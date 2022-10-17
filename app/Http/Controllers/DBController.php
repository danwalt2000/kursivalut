<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Log;
use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\CurrencyController;
use App\Models\Ads;
 
class DBController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public function getExchangeDirections ( $sell_buy, $currency )
    {
        $query = '_';
        if( $sell_buy == 'sell' || $sell_buy == 'buy'){
            $query = $sell_buy . $query;
        }
        if( array_key_exists( $currency, (new CurrencyController)->currencies) ){
            $query .= $currency;
        }
        $db_query = "where('type', 'like', '%' . $query . '%')";
        // return $this->getPosts();
        return Ads::where('type', 'like', "%" . $query . "%")
                  ->orderBy('date', 'desc')->take(100)->get();
    }

    public static function getPosts( $param = 'date', $asc_desc = 'desc', $date_range = 24, $direction = '' ){
        $time_range = 24;
        if(!empty($_GET["date"]) && filter_var($_GET["date"], FILTER_VALIDATE_INT)!== false ){
            $time_range = $_GET["date"];
        }

        $cut_by_time = time() - $time_range * 60 * 60;
        return Ads::where("date", ">", $cut_by_time)->orderBy($param, $asc_desc)->take(100)->get();
    }
    public static function countLatest( $param = 'date', $asc_desc = 'desc', $date_range = 24 ){
        $time_range = 24;
        if(!empty($_GET["date"]) && filter_var($_GET["date"], FILTER_VALIDATE_INT)!== false  ){
            $time_range = $_GET["date"];
        }

        $cut_by_time = time() - $time_range * 60 * 60;
        return Ads::where("date", ">", $cut_by_time)->orderBy($param, $asc_desc)->count();
    }
}