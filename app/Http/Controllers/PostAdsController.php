<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Http\Controllers\CurrencyController;
use App\Models\{Ads, Donetsk, Lugansk, Mariupol};
 
class PostAdsController extends CurrencyController
{
    public static function postNewAds( $ads_id )
    {
        $currency = new CurrencyController;
        $posts = new DBController;
        
        // $access_token = env('VK_TOKEN_FOR_POST');
        $access_token = Storage::get('/private/ivanov-token.txt');
        $group_id = "-218361718"; // группа, в которую постятся записи
        
        $ad = $posts->getPostById($ads_id); // Ads::where('vk_id', $ads_id)->take(1)->get();
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