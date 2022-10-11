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
    

    public static function getPosts()
    {
        $currency = new CurrencyController;
        $json_file = Storage::get('api.json');
        if ( !$json_file ){
            $access_token = "vk1.a.Hv_D01r4bJfnTOumY5rCtn7NyYSWLWWDJogEzbnBCkBaDTFWRMfsYHeiALSCFF0W-mAoiqjNK01HfC4n7D7DI_xNOBnVhLVmEcG7wyZ_qP6FENCZO_WSlWnjJDpRtXw--0xazEHvm_UxYqrR_WTRQVtcwzF-FYIMFHessTD0oHVBXpcZyJO-cPBTBmwhVWVf";
            $url = "https://api.vk.com/method/wall.get?access_token=" . $access_token . "&owner_id=" . $currency->publics["obmenvalut_donetsk"] . "&v=5.81&count=100";
            try {
                $response = Http::get($url);
            } catch(\Exception $exception) {
                Log::error($exception);
                return $currency->db_ads = DB::table('ads')->limit('100')->get();
            }
            $json = json_decode($response->getBody(), true);
            Storage::disk('local')->put('api.json', json_encode($json));
            $json_file = Storage::get('api.json');
        }

        $ads = json_decode($json_file, true);
        $currency->ads = $ads["response"]["items"];
        $currency->db_ads = (new ParseAdsController)->parseAd( $currency->ads );
        
        return $currency->db_ads;
    }
}