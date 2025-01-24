<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\DBController;
 
class PostAdsController extends CurrencyController
{
    public static function postToVk( $ads_id )
    {
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
        
        PostAdsController::sendHttp($url);
    }

    /*** 
        Схема работы серверов tg: один сервер слушает события в разных группах и отправляет 
        информацию на API приложения. Другой сервер администрирует аффилированные группы 
        и репостит полезные объявления. 
        Также у второго сервера есть бот, проводящий модерацию постов в афилированых группах:
        при публикации поста он отправляет сообщение на API приложения и получает ответ о полезности, 
        после чего либо удаляет либо оставляет сообщение.
    ***/
    public static function postToTg( $ad_object )
    {
        // если источник объявления - телеграм, то делаем репост
        if("tg" === $ad_object["domain"]){
            // $url_get_history = $url_base . "/api/messages.getHistory/?data[limit]=1&data[peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"];
            // $history = PostAdsController::sendHttp($url);

            $url = env("TG_LISTENER_DOMAIN") . "/api/messages.forwardMessages/?data[from_peer]=".$ad_object["channel"]."&data[to_peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[id][0]=" . $ad_object["vk_id"];
        }

        // проводим проверку, чтобы последнее сообщение в группе не было от того же пользователя
        // что и нынешнее: часто люди постят свои сообщения одновременно в несколько групп,
        // из-за чего в афилированной группе появляется несколько одинаковых сообщений подряд
        // $last_ad = DBController::getLastAdWithRate($ad_object["locale"]);
        // if(isset($last_ad->vk_user) && $last_ad->vk_user != $ad_object["vk_user"]){

        // Log::error($json->response->messages[0]->fwd_from->from_id->user_id);
        $last_ad_from_current_user = false;
        $last_ad = PostAdsController::getLastTgAd($ad_object);
        if(isset($last_ad->response->messages[0]->fwd_from->from_id->user_id) ){
            $last_ad_usr_id = $last_ad->response->messages[0]->fwd_from->from_id->user_id;
            if($last_ad_usr_id == $ad_object["vk_user"]){
                $last_ad_from_current_user = true;
            }
        }
        if(!$last_ad_from_current_user){
            PostAdsController::sendHttp($url);
        }
        // }
        // var_dump("--------------last ad -----------------", $last_ad);
        var_dump("--------------ad object ---------------", $ad_object);
        var_dump("--------------url ---------------", $url);
        // Log::error(isset($last_ad->vk_user) && $last_ad->vk_user != $ad_object["vk_user"]);
        // Log::error($last_ad->vk_user);
        // Log::error($ad_object["vk_user"]);
        // Log::error($url);
    }
    
    public static function repostToTg( $ad_object )
    {
        $url = env("TG_LISTENER_DOMAIN") . "/api/messages.forwardMessages/?data[from_peer]=@".$ad_object["channel"]."&data[to_peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[id][0]=" . $ad_object["vk_id"];
    }
    
    public static function sendToTg( $ad_object )
    {
        // http://127.0.0.1:9504/api/messages.sendMessage/?data[peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[message]=
        $url = env("TG_LISTENER_DOMAIN") . "/api/messages.forwardMessages/?data[from_peer]=@".$ad_object["channel"]."&data[to_peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[id][0]=" . $ad_object["vk_id"];
    }

    public static function getLastTgAd( $ad_object )
    {
        $url = env("TG_LISTENER_DOMAIN") ."/api/messages.getHistory/?data[peer]=@" . env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[limit]=1";
        $json = PostAdsController::getJson($ad_object);
        if(!empty($json) && !empty($json->success)){
            // Log::error("in last tg ad all passed");
            return $json;

        } 
    }

    // удаляет бесполезное объявление из моих групп
    public static function deleteInTg( $ad, $locale )
    {
        $post = $ad[0];
        if(isset($post["id"]) && !empty($post["id"]) && isset($locale["name"]) && !empty($locale["name"])  ){
            $url = env("TG_LISTENER_DOMAIN") . "/api/channels.deleteMessages/?data[channel]=@" . env("TG_CHANNEL_DOMAIN") . $locale["name"] . "&data[id][0]=" . $post["id"];
            
            PostAdsController::sendHttp($url);
        }
    }
    
    // отправляет уведомление пользователю, что его сообщение удалено
    public static function sendNoteInTg( $ad, $locale )
    {
        $post = $ad[0];
        // var_dump($post);
        if(isset($post["id"]) && !empty($post["id"]) && isset($post["from_id"]->user_id) && !empty($post["from_id"]->user_id) && isset($post["message"]) ){
            $text = "Ваше сообщение в группе @" . env("TG_CHANNEL_DOMAIN") . $locale["name"] . " не прошло модерацию и было удалено. Пожалуйста, ознакомьтесь с правилами публикации объявлений в группе. Текст вашего сообщения:\n«" . $post["message"] . "»";
            $text_replaced = str_replace(" ", "%20", $text);
            $url = env("TG_LISTENER_DOMAIN") . "/api/messages.sendMessage/?data[peer]=" . $post["from_id"]->user_id . "&data[message]=" . $text_replaced;
            
            PostAdsController::sendHttp($url);
        }
    }

    public static function sendHttp($url)
    {
        try {
            $response = Http::get($url);
        } catch(\Exception $exception) {
            Log::error($exception);
        }
        if(!empty($response)) return $response;
    }

    public static function getJson($ad_object)
    {
        $url = env("TG_LISTENER_DOMAIN") . "/api/messages.getHistory/?data[peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] ."&data[limit]=1";
        $response = PostAdsController::sendHttp($url);
        
        return empty($response) ? null : json_decode($response->body());
    }
}