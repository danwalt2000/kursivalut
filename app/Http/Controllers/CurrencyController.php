<?php
 
namespace App\Http\Controllers;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
 
class CurrencyController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    
    public $ads = [];
    public $sell_dollar = [];
    public $db_ads = [];
    public $last_ad_time = '';

    private function getPosts()
    {
        $json_file = Storage::get('api.json');
        if ( !$json_file ){
            $url = "https://api.vk.com/method/wall.get?access_token=vk1.a.Hv_D01r4bJfnTOumY5rCtn7NyYSWLWWDJogEzbnBCkBaDTFWRMfsYHeiALSCFF0W-mAoiqjNK01HfC4n7D7DI_xNOBnVhLVmEcG7wyZ_qP6FENCZO_WSlWnjJDpRtXw--0xazEHvm_UxYqrR_WTRQVtcwzF-FYIMFHessTD0oHVBXpcZyJO-cPBTBmwhVWVf&domain=obmenvalut_donetsk&v=5.81&count=100";
            $response = Http::get($url);
            $json = json_decode($response->getBody(), true);
            Storage::disk('local')->put('api.json', json_encode($json));
            $json_file = Storage::get('api.json');
        }

        $ads = json_decode($json_file, true);
        $this->parseAd( $ads );
        
        return $ads;
    }

    private function parseAd( $json ){
        $this->ads = $json["response"]["items"];
        $ads = $this->ads;
        $patterns = [
            "sell_dollar" => "/[Пп]род.*(\$|дол|син|зел)(.*?\d{2})/",
            "buy_dollar" => "/[Кк]уп.*(\$|дол|син|зел)(.*?\d{2})/",
            "phone_number" => "/[+0-9-]{10,20}/"
        ];
        $matches = [];
        foreach($ads as $ad){
            $text = $ad["text"];
            $group = "obmenvalut_donetsk";
            $owner_and_id = $ad["owner_id"] . "_" . $ad["id"];
            $link = "https://vk.com/" . $group . "?w=wall" . $owner_and_id . "%2Fall";
            $this->last_ad_time = DB::table('ads')->orderBy("date", "desc")->first();
            preg_match($patterns["sell_dollar"], $text, $match);

            $type = false;
            if(count($match) > 0){
                $type = 'sell_dollar';
            }
            $is_in_table = DB::table('ads')->where('vk_id', '=', $ad["id"])->get();
            if( !count($is_in_table) ){
                DB::table('ads')->insert([
                    'vk_id'      => $ad["id"],
                    'owner_id'   => $ad["owner_id"],
                    'date'       => $ad["date"],
                    'text'       => $ad["text"],
                    'link'       => $link,
                    'group'      => $group,
                    'type'       => $type
                ]);
            }
        }
        
        $this->sell_dollar = DB::table('ads')->
                where('type', '=', 'sell_dollar')->
                orderBy("date", "desc")->get();
        $this->db_ads = DB::table('ads')->get();;
    }

    public function index()
    {
        return view('currency', [
            'url' => $this->getPosts(),
            'ads' => $this->ads, 
            'sell_dollar' => $this->sell_dollar,
            'db_ads' => $this->db_ads,
            'last_time' => $this->last_ad_time->date
        ]);
    }
}