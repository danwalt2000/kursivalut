<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use Config;
use App\Http\Controllers\CurrencyController;
 
class GetAdsController extends CurrencyController
{
    public $domain;
    public $channel;
    public $parser;

    public function __construct()
    {
        $this->parser = new ParseAdsController;
    }

    /**
     * Получение новых объявлений по запросу на API площадок
     *
     * @param  array $channel - данные о группе, с которой беруться объявления 
     * @return object (ads)
     */
    public function getVkAds( $channel, $locale )
    {
        $posts = new DBController;

        $this->channel = $channel;          // vk or tg
        $api_keys = Config::get('common.api_keys')['vk'];
        $table = $locale['name'];
        
        $url = $this->getApiLink();
        
        try {
            $response = Http::get($url);
        } catch(\Exception $exception) {
            Log::error($exception);
            return $posts::getPosts( $table );
        }
        $json = json_decode($response->getBody(), true);
        
        // бывает, что от API приходит ошибка
        $errors = $api_keys["error_key"]; // у API разные названия ключа: error и errors
        if ( !empty( $json[$errors] ) ){ 
            Log::error($url);
            return Log::error($json);
        }
        
        $items = $json["response"][ $api_keys['items_key'] ];
        
        $result_ads = $this->parser->parseAd( $items, $channel, $locale );

        return $posts::getPosts( $table );
    }

    /**
     * Получение полного url, к которому неомходимо обратиться за json'ом
     * @return string - url API
     */
    public function getApiLink()
    {
        // $access_token = Storage::get('/private/token.txt');
        $access_token = env('VK_TOKEN');
        $count = 10;
        
        $api = Config::get('common.api_keys')['vk'];;
        $url_base = $api['url_key'];

        $url_base .= $access_token . '&'; // к vk добавляем токен
        $url = $url_base . $api['url_channel_key'] . '=' . $this->channel['id']; // + &owner_id=
        $url .= '&' . $api['url_limit_key'] . '=' . $count; // &count=
        return $url;
    }

    // получение нового объявления по обращению к API этого приложения
    public function addPostByAPI(){
        if( empty($_POST) || 
            empty($_POST["password"]) || 
            env('API_PASSWORD') != $_POST["password"]  ) abort(403);
        $json = json_decode($_POST["content"]);
        $message = $json->message;
        if( empty($message->peer_id) || empty($message->peer_id->channel_id) ) return;
        $channel_id = $message->peer_id->channel_id;

        $locales = Config::get('locales');
        $target_locale = [];
        
        foreach($locales as $locale){
            if( array_key_exists($channel_id, $locale['tg']) ){
                $target_locale = $locale;
                break;
            }
        }

        if( !empty($target_locale) ){
            $for_parsing = [ (array) $message ];
            // Log::error(json_encode($for_parsing));
            $parsed_ad = $this->parser->parseAd( $for_parsing, $locale['tg'][$channel_id], $locale, 'tg' );
    
            return $parsed_ad;
        }
    }
}