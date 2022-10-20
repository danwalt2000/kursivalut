<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Psr\Http\Message\RequestInterface;
use Log;
use App\Http\Controllers\DBController;
use App\Http\Controllers\CurrencyController;
 
class AjaxController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public function ajax(Request $request){
        $currency_controller = new CurrencyController;
        $to_view = $currency_controller->to_view;

        $sellbuy = $request->query('sellbuy');
        if( empty($sellbuy) ) $sellbuy = '';
        $currency = $request->query('currency');
        if( empty($currency) ) $currency = '';
        $offset = $request->query('offset');
        if( empty($currency) ) $offset = 0;
        
        // $sort = $request->query('sort');
        // $order = $request->query('order');

        $to_view['ads'] = DBController::getPosts("get", $sellbuy, $currency, '', $offset );
        // $input = $request->all();
        // var_dump($input);
        return view('feed', $to_view);;
    }

    public function ajaxPost(Request $request){
        $input = $request->all();
        $ad = DBController::getPhone($input);
        if( empty($ad->phone) ){
            return $ad;
        }
          
        // Log::info($ad);
        return $ad->phone;
        // return response()->json(['success'=>'Got Simple Ajax Request.']);
    }
}