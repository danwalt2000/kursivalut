<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Log;
use App\Http\Controllers\CurrencyController;
 
class GetAdsController extends CurrencyController
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    

    public static function getPosts( $group_id )
    {
        $currency = new CurrencyController;
        
        $access_token = "vk1.a.Hv_D01r4bJfnTOumY5rCtn7NyYSWLWWDJogEzbnBCkBaDTFWRMfsYHeiALSCFF0W-mAoiqjNK01HfC4n7D7DI_xNOBnVhLVmEcG7wyZ_qP6FENCZO_WSlWnjJDpRtXw--0xazEHvm_UxYqrR_WTRQVtcwzF-FYIMFHessTD0oHVBXpcZyJO-cPBTBmwhVWVf";
        $count = 10;
        
        // если таблица пустая, запрашиваем больше записей
        if( !( DB::table('ads')->first() ) ){
            $count = 100;
        }
        $url = "https://api.vk.com/method/wall.get?access_token=" . $access_token . "&owner_id=" . $group_id . "&v=5.81&count=" . $count;
        try {
            $response = Http::get($url);
        } catch(\Exception $exception) {
            Log::error($exception);
            return $currency::getLatest();
        }
        $json = json_decode($response->getBody(), true);
        
        $currency->ads = $json["response"]["items"];
        $currency->db_ads = ParseAdsController::parseAd( $currency->ads, $group_id );
        
        return $currency->db_ads;
    }
}


// логика сохранения ответа в файл
// $json_file = Storage::get('api.json');
// if ( !$json_file ){
//     Storage::disk('local')->put('api.json', json_encode($json));
//     $json_file = Storage::get('api.json');
// }