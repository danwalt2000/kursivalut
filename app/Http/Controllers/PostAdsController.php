<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Http\Controllers\CurrencyController;
use App\Models\Ads;
 
class PostAdsController extends CurrencyController
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    

    public static function postNewAds( $ads_id )
    {
        $currency = new CurrencyController;
        $posts = new DBController;
        
        // $access_token = env('VK_TOKEN_FOR_POST');
        $access_token = Storage::get('/private/ivanov-token.txt');
        $group_id = env('VK_GROUP_ID');
        
        $ad = Ads::where('vk_id', $ads_id)->take(1)->get();
        if( !count($ad) ){
            return;
        }
        $ad = $ad[0]; 
        $url = "https://api.vk.com/method/wall.post?access_token=" . $access_token . "&owner_id=" . $group_id . "&v=5.81&from_group=1&message=" . $ad['content'] . "&copyright=" . $ad['link'];
        try {
            Http::get($url);
        } catch(\Exception $exception) {
            Log::error($exception);;
        }
    }
}