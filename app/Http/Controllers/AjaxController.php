<?php
 
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Psr\Http\Message\RequestInterface;
use Log;
use App\Http\Controllers\DBController;
 
class AjaxController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
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