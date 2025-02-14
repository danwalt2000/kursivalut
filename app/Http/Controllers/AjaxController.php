<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Log;
use Config;
use App\Http\Controllers\DBController;
use App\Http\Controllers\CurrencyController;
 
class AjaxController extends Controller
{
    public function ajax(Request $request){
        $to_view = (new CurrencyController)->to_view;
        $table = SessionController::getHost()["table"];

        $sellbuy = $request->query('sellbuy') ?? '';
        
        $currency = $request->query('currency') ?? '';
        
        $offset = $request->query('offset') ?? 0;
        
        $search = $request->query('search') ?? '';

        $to_view['ads'] = DBController::getPosts( $table, "get", $sellbuy, $currency, $search, $offset );
        return view('parts.feed', $to_view);;
    }

    public function ajaxPost(Request $request){
        $input = $request->all();
        $ad = DBController::getPhone($input);
        if( empty($ad->phone) ) return $ad; 
        return $ad->phone;
    }
}