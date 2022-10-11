<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Log;
use App\Http\Controllers\GetAdsController;
 
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
    public $get_posts;
    // public $last_ad_time = '';
    public $publics = [
        "obmenvalut_donetsk" => "-87785879", // 5
        "obmen_valut_donetsk" => "-92215147", // 5
        "obmenvalyut_dpr" => "-153734109", // 20
        "club156050748" => "-156050748",  // 20
        "obmen_valut_dnr" => "-193547744", // 60
        "donetsk_obmen_valyuta" => "-174075254" //60
    ];
    public $directions = [
       "sell_dollar" => "Продажа доллара",
       "buy_dollar" => "Покупка доллара",
       "sell_euro" => "Продажа евро",
       "buy_euro" => "Покупка евро",
       "sell_hrn" => "Продажа гривны",
       "buy_hrn" => "Покупка гривны",
       "sell_cashless" => "Продажа безнала руб.",
       "buy_cashless" => "Покупка безнала руб."
    ];

    public function getExchangeDirections ( $direction )
    {
        $query = htmlspecialchars( $direction );
        return DB::table('ads')->
                where('type', 'like', "%" . $query . "%")->limit("100")->
                orderBy("date", "desc")->get();
    }

    public function show( $currency )
    {
        return view('currency', [
            'ads' => $this->getExchangeDirections($currency),
            'directions' => $this->directions
        ]);
    }

    public function index()
    {
        $ads = (new GetAdsController)::getPosts();
        
        return view('currency', [
            'ads' => $ads,
            'directions' => $this->directions,
        ]);
    }
}