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
    public $db_ads = [];
    public $last_ad_time = '';

    public function getExchangeDirections ( $direction )
    {
        return DB::table('ads')->
                where('type', 'like', "%" . $direction . "%")->
                orderBy("date", "desc")->get();
    }

    private function getPosts()
    {
        $json_file = Storage::get('api.json');
        if ( !$json_file ){
            $publics = [
                "obmenvalut_donetsk" => "-87785879", // 5
                "obmen_valut_donetsk" => "-92215147", // 5
                "obmenvalyut_dpr" => "-153734109", // 20
                "club156050748" => "-156050748",  // 20
                "obmen_valut_dnr" => "-193547744", // 60
                "donetsk_obmen_valyuta" => "-174075254" //60
            ];
            $access_token = "vk1.a.Hv_D01r4bJfnTOumY5rCtn7NyYSWLWWDJogEzbnBCkBaDTFWRMfsYHeiALSCFF0W-mAoiqjNK01HfC4n7D7DI_xNOBnVhLVmEcG7wyZ_qP6FENCZO_WSlWnjJDpRtXw--0xazEHvm_UxYqrR_WTRQVtcwzF-FYIMFHessTD0oHVBXpcZyJO-cPBTBmwhVWVf";
            $url = "https://api.vk.com/method/wall.get?access_token=" . $access_token . "&owner_id=" . $publics["obmen_valut_donetsk"] . "&v=5.81&count=100";
            try {
                $response = Http::get($url);
            } catch(\Exception $exception) {
                Log::error($exception);

            }
            $json = json_decode($response->getBody(), true);
            Storage::disk('local')->put('api.json', json_encode($json));
            $json_file = Storage::get('api.json');
        }

        $ads = json_decode($json_file, true);
        $this->ads = $ads;
        $this->parseAd( $ads );
        
        return $ads;
    }

    private function parseAd( $json )
    {
        $this->ads = $json["response"]["items"];
        $ads = $this->ads;
        $patterns = [
            "sell_dollar"      => "/[Пп]род.*(\$|дол|син|зел|💵)(.*?\d{2})/",
            "sell_euro"        => "/[Пп]род.*(\€|евро)(.*?\d{2})/",
            "sell_hrn"         => "/[Пп]род.*([Гг]рн|грив|[Пп]риват|[Оо]щад|[Мм]оно)/",
            "sell_cashless"    => "/[Пп]род.*([Cс]бер|[Тт]иньк)/",
            "buy_dollar"       => "/[Кк]уп.*(\$|дол|син|зел|💵)(.*?\d{2})/",
            "buy_euro"         => "/[Кк]уп.*(\€|евро)(.*?\d{2})/",
            "buy_hrn"          => "/[Кк]уп.*([Гг]рн|грив|[Пп]риват|[Оо]щад|[Мм]оно)/",
            "buy_cashless"     => "/[Кк]уп.*([Cс]бер|[Тт]иньк)/"
            // "phone_number" => "/[+0-9-]{10,20}/"
        ];
        foreach($ads as $ad){
            $text = $ad["text"];
            $this->last_ad_time = DB::table('ads')->orderBy("date", "desc")->first();
            
            $group = "obmenvalut_donetsk";
            $owner_and_id = $ad["owner_id"] . "_" . $ad["id"];
            $link = "https://vk.com/" . $group . "?w=wall" . $owner_and_id . "%2Fall";
            
            $type = '';
            foreach( $patterns as $key => $pattern ){
                $test_matches = preg_match($pattern, $text, $match);
                if( !empty($test_matches) ){
                    if( empty($type) ){
                        $type = $key;
                    } else{
                        $type = $type . ", " . $key;
                    }
                }
            }
            $is_id_in_table = DB::table('ads')
                                ->where('vk_id', '=', $ad["id"])
                                ->where('owner_id', '=', $ad["owner_id"])
                                ->get();
            $is_text_in_table = DB::table('ads')->where('text', '=', $ad["text"])->get();
            if( count($is_text_in_table) ){
                DB::table('ads') ->where('text', '=', $ad["text"])->update([
                    'vk_id'      => $ad["id"],
                    'date'       => $ad["date"]
                ]);
            } elseif( !count($is_id_in_table) && $ad["from_id"] != $ad["owner_id"] ){
                DB::table('ads')->insert([
                    'vk_id'      => $ad["id"],
                    'vk_user'    => $ad["from_id"],
                    'owner_id'   => $ad["owner_id"],
                    'date'       => $ad["date"],
                    'text'       => $ad["text"],
                    'link'       => $link,
                    'group'      => $group,
                    'type'       => $type
                ]);
            } 
        }
        
        $this->db_ads = DB::table('ads')->get();
    }

    public function show( $currency )
    {
        return view('currency', ['ads' => $this->getExchangeDirections($currency)]);
    }

    public function index()
    {
        return view('currency', [
            'url' => $this->getPosts(),
            'ads' => $this->db_ads,
            'last_time' => $this->last_ad_time->date
        ]);
    }
}