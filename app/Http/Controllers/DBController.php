<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
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

    public static function getPosts( $get_or_count = "get", $sell_buy = '', $currency = '' ){
        $sort = 'date';
        if(!empty($_GET["sort"]) && str_contains( "date rate popularity", $_GET["sort"]) ){
            $sort = $_GET["sort"];
        } 
        $asc_desc = 'desc';
        if(!empty($_GET["order"]) && str_contains( "asc desc", $_GET["order"]) ){
            $asc_desc = $_GET["order"];
        }
        
        $time_range = 24;
        if(!empty($_GET["date"]) && filter_var($_GET["date"], FILTER_VALIDATE_INT)!== false ){
            $time_range = $_GET["date"];
        }
        $query = '';
        if( $sell_buy != "all" && !empty($sell_buy) || !empty($currency) ){
            $query = $sell_buy . '_' . $currency;
        }
        $cut_by_time = time() - $time_range * 60 * 60;
        return Ads::where("date", ">", $cut_by_time)
                  ->where('type', 'like', "%" . $query . "%")
                  ->orderBy($sort, $asc_desc)->take(100)->$get_or_count();
    }
}