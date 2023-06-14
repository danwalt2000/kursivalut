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
    public $api_keys;
    public $channel;
    public $table;

    /**
     * Получение новых объявлений
     *
     * @param  array $channel - данные о группе, с которой беруться объявления 
     * @return object (ads)
     */
    public function getNewAds( $channel, $locale )
    {
        $currency = new CurrencyController;
        $posts = new DBController;
        $parser = new ParseAdsController;

        $this->channel = $channel;          // vk or tg
        $this->domain = $channel['domain']; // vk or tg
        $this->api_keys = Config::get('common.api_keys')[ $this->domain ];
        $table = $locale['name'];
        
        $url = $this->getApiLink();
        
        try {
            $response = Http::get($url);
        } catch(\Exception $exception) {
            Log::error($exception);
            return $posts::getPosts( $this->table );
        }
        $json = json_decode($response->getBody(), true);
        
        // бывает, что от API приходит ошибка
        $errors = $this->api_keys["error_key"]; // у API разные названия ключа: error и errors
        if ( !empty( $json[$errors] ) ){ 
            Log::error($url);
            return Log::error($json);
        }
        
        $items = $json["response"][ $this->api_keys['items_key'] ];
        $currency->db_ads = $parser->parseAd( $items, $channel, $locale );
        
        return $currency->db_ads;
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
        
        $api = $this->api_keys;
        $url_base = $api['url_key'];

        if( 'vk' == $this->domain) $url_base .= $access_token . '&'; // к vk добавляем токен
        $url = $url_base . $api['url_channel_key'] . '=' . $this->channel['id']; // + &owner_id=
        $url .= '&' . $api['url_limit_key'] . '=' . $count; // &count=
        return $url;
    }
}