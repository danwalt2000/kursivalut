<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Log;
use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\ScheduleController;
 
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
    
    public $publics = [
        "obmenvalut_donetsk"    => ["id" => "-87785879",  "time" => "everyFiveMinutes"],    // 5
        "obmen_valut_donetsk"   => ["id" => "-92215147",  "time" => "everyFiveMinutes"],    // 5
        "obmenvalyut_dpr"       => ["id" => "-153734109", "time" => "everyThirtyMinutes"],  // 30
        "club156050748"         => ["id" => "-156050748", "time" => "everyThirtyMinutes"],  // 20
        "obmen_valut_dnr"       => ["id" => "-193547744", "time" => "hourly"],              // 60
        "donetsk_obmen_valyuta" => ["id" => "-174075254", "time" => "hourly"]               //60
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

    public function __construct()
    {
        $this->db_ads = $this->getLatest();
        // ScheduleController::schedule
    }

    public function getExchangeDirections ( $direction )
    {
        $query = htmlspecialchars( $direction );
        return DB::table('ads')->
                where('type', 'like', "%" . $query . "%")->limit("100")->
                orderBy("date", "desc")->get();
    }

    public static function getLatest(){
        return DB::table('ads')->limit('100')->orderBy("date", "desc")->get();
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
        return view('currency', [
            'ads' => $this->db_ads,
            // 'ads' => GetAdsController::getPosts( "-87785879" ),
            'directions' => $this->directions,
        ]);
    }
}