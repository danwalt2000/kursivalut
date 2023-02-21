<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Http\Controllers\CurrencyController;
use App\Models\Ads;
 
class GetAdsController extends CurrencyController
{
    public static function getNewAds( $channel )
    {
        $currency = new CurrencyController;
        $posts = new DBController;
        $domain = $channel['domain']; // vk or tg
        $channel_id = $channel['id'];
        $parser = new ParseAdsController;
        $vars = new VarsController;
        $api_keys = $vars->api_keys[$domain];
        
        // $access_token = env('VK_TOKEN');
        $access_token = Storage::get('/private/token.txt');
        $count = 10;
        // $count = 100;
        // $count = 1000;
        
        // если таблица пустая, запрашиваем больше записей
        if( Ads::count() == 0 ){
            $count = 100;
        }
        $url = "https://api.vk.com/method/wall.get?access_token=" . $access_token . "&owner_id=" . $channel_id . "&v=5.81&count=" . $count;
        // Log::channel('command')->info($url);
        try {
            $response = Http::get($url);
        } catch(\Exception $exception) {
            Log::error($exception);
            return $posts::getPosts();
        }
        $json = json_decode($response->getBody(), true);
        
        // бывает, что от API приходит ошибка
        if ( isset( $json[ $api_keys["error_key"] ] ) ){ 
            return Log::error($json);
        }
        
        $currency->db_ads = $parser->parseAd( $json["response"]["items"] );
        return$currency->db_ads;
    }
}