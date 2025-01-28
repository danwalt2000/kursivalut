<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Http\Controllers\DBController;
 
class PostAdsController extends Controller
{
    public static function postToVk( $ads_id )
    {
        $posts = new DBController;
        
        // $access_token = env('VK_TOKEN_FOR_POST');
        $access_token = Storage::get('/private/ivanov-token.txt');
        $group_id = "-218361718"; // группа, в которую постятся записи
        
        $ad = $posts->getPostById($ads_id); // Ads::where('vk_id', $ads_id)->take(1)->get();
        if( !count($ad) ) return; 

        $ad = $ad[0]; 
        $url = "https://api.vk.com/method/wall.post?access_token=" . $access_token . "&owner_id=" . $group_id . "&v=5.81&from_group=1&message=" . $ad['content'] . "&copyright=" . $ad['link'];
        
        PostAdsController::sendHttp($url);
    }

    /*** 
        Схема работы серверов tg: один сервер слушает события в разных группах и отправляет 
        информацию на API приложения. Другой сервер администрирует аффилированные группы 
        и репостит полезные объявления. 
        Также у второго сервера есть бот, проводящий модерацию постов в афилированых группах
        и отправляющий сообщением объявления, которые невозможно репостнуть:
        при модерации поста он отправляет сообщение на API приложения и получает ответ о полезности, 
        после чего либо удаляет либо оставляет сообщение.
    
        метод репостит либо отправляет сообщение в афилированную группу tg
     * @param  ad_object - формируется в ParseAdsController->parseAd
     * @return void
     */
    public static function postToTg( $ad_object )
    {
        // если источник объявления - телеграм, то пытаемся сделать репост
        if("tg" === $ad_object["domain"]){
            $url = env("TG_LISTENER_DOMAIN") . "/api/messages.forwardMessages/?data[from_peer]=".$ad_object["channel"]."&data[to_peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[id][0]=" . $ad_object["vk_id"];
            // проводим проверку, чтобы последнее сообщение в группе не было от того же пользователя
            // что и нынешнее: часто люди постят свои сообщения одновременно в несколько групп,
            // из-за чего в афилированной группе появляется несколько одинаковых сообщений подряд
            $last_ad_from_current_user = false;
            $last_ad = PostAdsController::getLastTgAd($ad_object);
            if(isset($last_ad->response->messages[0]->fwd_from->from_id->user_id) ){
                $last_ad_usr_id = $last_ad->response->messages[0]->fwd_from->from_id->user_id;
                if($last_ad_usr_id == $ad_object["vk_user"]){
                    $last_ad_from_current_user = true;
                }
            }
            if(!$last_ad_from_current_user){
                // непосредственно репост
                $repost_response = PostAdsController::sendHttp($url);
                
                if(!empty($repost_response)){
                    $repost_json = json_decode($repost_response->body());
                    // бывает, что сообщение нельзя переслать из-за ошибки или
                    // запрета на репост в политике группы.
                    // В таком случае просто копируем сообщение в группу
                    // со ссылкой на сайт этого приложения
                    if(empty($repost_json) || empty($repost_json->success)){
                        $repost_response = PostAdsController::sendToTg($ad_object);
                        
                    }
                }
            }
        }
    }
    
    public static function repostToTg( $ad_object )
    {
        $url = env("TG_LISTENER_DOMAIN") . "/api/messages.forwardMessages/?data[from_peer]=@".$ad_object["channel"]."&data[to_peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[id][0]=" . $ad_object["vk_id"];
    }

    public static function getLastTgAd( $ad_object )
    {
        $url = env("TG_LISTENER_DOMAIN") ."/api/messages.getHistory/?data[peer]=@" . env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] . "&data[limit]=1";
        $json = PostAdsController::getJson($ad_object);
        if(!empty($json) && !empty($json->success)){
            return $json;
        } 
    }

    // удаление спама + отправка сообщения об удалении
    public static function modetateTg( $remove_ad_object )
    {
        PostAdsController::deleteInTg($remove_ad_object);
        $remove_ad_object["moderation"] = "1";
        PostAdsController::sendToTg($remove_ad_object);
    }

    // удаляет бесполезное объявление из афилированных групп
    public static function deleteInTg( $remove_ad_object )
    {
        // var_dump($remove_ad_object);
        if(isset($remove_ad_object["id"]) && !empty($remove_ad_object["id"]) ){
            $url = env("TG_BOT_DOMAIN") . "/api/channels.deleteMessages/?data[channel]=@" . env("TG_CHANNEL_DOMAIN") . $remove_ad_object["locale"] . "&data[id][0]=" . $remove_ad_object["id"];
            
            PostAdsController::sendHttp($url);
        }
    }

    // используется, чтобы слать сообщение в афилированные группы:
    // если не удается сделать репост либо объявление взято не из tg,
    // отправляется текст объявления. Если удален спам, то в группу
    // отправляется уведомление об удалении
    public static function sendToTg( $ad_object )
    {
        // var_dump($ad_object);
        // уведомление-модерация о том, что спам удален
        if(isset( $ad_object["moderation"]) ){

            $username = "";
            if(isset($ad_object["from_id"]->user_id)){
                $username = "пользователя @" . PostAdsController::getTgUserInfo($ad_object["from_id"]->user_id) . " ";
            } 
            var_dump("-----------------", $ad_object["from_id"]->user_id);
            var_dump($username);
            $ad_content = "Сообщение " . $username . "не прошло модерацию и было удалено. Пожалуйста, ознакомьтесь с правилами публикации объявлений в группе";
        } else{
            $subdomain = $ad_object["locale"] == "donetsk" ? "" : $ad_object["locale"] . ".";
            $link_to_site = "https://" . $subdomain . "kursivalut.ru/?wall=" . $ad_object["owner_id"] . "_" . $ad_object["vk_id"];
            $ad_content = $ad_object["content"] . "\n❗️❗️❗️Объявление взято с сайта  👉 " . $link_to_site;
        }

        $text = str_replace(" ", "%20", $ad_content);
        $to_peer = env("TG_CHANNEL_DOMAIN") . $ad_object["locale"];
        $url = env("TG_BOT_DOMAIN") . "/api/messages.sendMessage/?data[peer]=@". $to_peer . "&data[message]=" . $text;
        
        PostAdsController::sendHttp($url);
    }

    // Получает никнейм пользователя по id
    public static function getTgUserInfo( $id )
    {
        $userinfo = '';
        $url = env("TG_LISTENER_DOMAIN") . "/api/getInfo/?id=" . $id;
        $response = PostAdsController::sendHttp($url);
        
        if(!empty($response)){
            $json = json_decode($response->body());
            if(isset($json->success) && !empty($json->success)){
                if(isset($json->response->User->username)){
                    $userinfo = $json->response->User->username;
                }
                // if(isset($json->response->User->first_name)){
                //     $userinfo .= " " . $json->response->User->first_name;
                // }
            }
            
        };
        return $userinfo;
    }

    // не используется
    public static function sendNoteInTg( $ad_object )
    {
        if(isset($ad_object["vk_id"]) && !empty($ad_object["vk_id"]) 
        // && isset($post["from_id"]->user_id) && !empty($post["from_id"]->user_id) && isset($post["message"]) 
        ){
            $text = "Ваше сообщение в группе @". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"]." не прошло модерацию и было удалено. Пожалуйста, ознакомьтесь с правилами публикации объявлений в группе. Текст вашего сообщения:\n«" .$ad_object["content"] . "»";
            $custom_ad_object = $ad_object;
            $custom_ad_object["content"] = $text;
            $custom_ad_object["moderation"] = "1";
            // $url = env("TG_LISTENER_DOMAIN") . "/api/messages.sendMessage/?data[peer]=" . $post["from_id"]->user_id . "&data[message]=" . $text_replaced;
            PostAdsController::sendToTg($custom_ad_object);
            
        }
    }

    // обертка для отправки get-запросов
    public static function sendHttp($url)
    {
        try {
            $response = Http::get($url);
        } catch(\Exception $exception) {
            Log::error($exception);
        }
        if(!empty($response)) return $response;
    }

    // обращение к tg-API
    public static function getJson($ad_object)
    {
        $url = env("TG_LISTENER_DOMAIN") . "/api/messages.getHistory/?data[peer]=@". env("TG_CHANNEL_DOMAIN") . $ad_object["locale"] ."&data[limit]=1";
        $response = PostAdsController::sendHttp($url);
        
        return empty($response) ? null : json_decode($response->body());
    }
}