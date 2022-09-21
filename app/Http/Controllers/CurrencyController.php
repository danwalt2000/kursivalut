<?php
 
namespace App\Http\Controllers;
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Facades\Storage;
 
class CurrencyController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    
    private function getPosts()
    {
        $json_file = Storage::get('api.json');
        if ( $json_file ){
            return json_decode($json_file, true);
        }

        $url = "https://api.vk.com/method/wall.get?access_token=vk1.a.Hv_D01r4bJfnTOumY5rCtn7NyYSWLWWDJogEzbnBCkBaDTFWRMfsYHeiALSCFF0W-mAoiqjNK01HfC4n7D7DI_xNOBnVhLVmEcG7wyZ_qP6FENCZO_WSlWnjJDpRtXw--0xazEHvm_UxYqrR_WTRQVtcwzF-FYIMFHessTD0oHVBXpcZyJO-cPBTBmwhVWVf&domain=obmenvalut_donetsk&v=5.81&count=100";
        $response = Http::get($url);
        // $owner_and_id = $response[0]->owner_id . "_" . $response[0]->id;
        // $post_link = "https://vk.com/obmenvalut_donetsk?w=wall" . $owner_and_id . "%2Fall";
        $json = json_decode($response->getBody(), true);
        Storage::disk('local')->put('api.json', json_encode($json));
        return $json;
    }

    

    public function index()
    {
        return view('currency', ['url' => $this->getPosts()]);
    }
}