<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Log;
use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\ParseAdsController;
use App\Http\Controllers\DBController;
use App\Models\Ads;
 
class SessionController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

    public static function isAllowed() {
        $lastSubmit = session()->get('last_submit');
        $is_allowed = true;
        if (!empty($lastSubmit) ){
            $is_allowed = !($lastSubmit > (time() - 10 * 60));
        }
        return $is_allowed;
     }
     
     public static function updateAllowed() {
        session()->put('last_submit', time());
     }

     public static function nextSubmit(){
        $time_to_next_submit = session()->get('last_submit') + 10 * 60 - time() ;
        return date('i мин. s сек.', $time_to_next_submit);
    }
}