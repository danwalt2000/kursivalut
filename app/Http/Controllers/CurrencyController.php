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
    public $sellDollars = [];
    public $db_ads = [];

    private function getPosts()
    {
        $json_file = Storage::get('api.json');
        if ( $json_file ){
            $ads = json_decode($json_file, true);
            $this->parseAd( $ads );
            return $ads;
        }

        $url = "https://api.vk.com/method/wall.get?access_token=vk1.a.Hv_D01r4bJfnTOumY5rCtn7NyYSWLWWDJogEzbnBCkBaDTFWRMfsYHeiALSCFF0W-mAoiqjNK01HfC4n7D7DI_xNOBnVhLVmEcG7wyZ_qP6FENCZO_WSlWnjJDpRtXw--0xazEHvm_UxYqrR_WTRQVtcwzF-FYIMFHessTD0oHVBXpcZyJO-cPBTBmwhVWVf&domain=obmenvalut_donetsk&v=5.81&count=100";
        $response = Http::get($url);
        // $owner_and_id = $response[0]->owner_id . "_" . $response[0]->id;
        // $post_link = "https://vk.com/obmenvalut_donetsk?w=wall" . $owner_and_id . "%2Fall";
        $json = json_decode($response->getBody(), true);
        Storage::disk('local')->put('api.json', json_encode($json));
        
        return $json;
    }

    private function parseAd( $json ){
        $this->ads = $json["response"]["items"];
        $ads = $this->ads;
        $pattern = "/[Пп]род.*(\$|дол|син|зел)(.*?\d{2})/";
        $matches = [];
        foreach($ads as $ad){
            $text = $ad["text"];
            $group = "obmenvalut_donetsk";
            $owner_and_id = $ad["owner_id"] . "_" . $ad["id"];
            $link = "https://vk.com/" . $group . "?w=wall" . $owner_and_id . "%2Fall";
            // DB::insert('insert into ads (vk_id, owner_id, date, text, link, group) values (?, ?, ?, ?, ?, ?)', [$ad["id"], $ad["owner_id"], $ad["date"], $ad["text"], $link, $group ]);
            // DB::table('ads')->insert([
            //     'vk_id' => $ad["id"],
            //     'owner_id' => $ad["owner_id"],
            //     'date' => $ad["date"],
            //     'text' => $ad["text"],
            //     'link' => $link,
            //     'group' => $group
            // ]);
            preg_match($pattern, $text, $match);
            if(count($match) > 0){
                array_push($matches, $ad);
            }
        }
        
        $this->sellDollars = $matches;
        $this->db_ads = DB::table('ads')->get();;
    }

    public function index()
    {
        return view('currency', [
            'url' => $this->getPosts(),
            'ads' => $this->ads, 
            'sellDollars' => $this->sellDollars,
            'db_ads' => $this->db_ads
        ]);
    }
}