<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Log;
use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\ScheduleController;
use App\Models\Ads;
 
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
    public $currencies = [
       "dollar" => "Доллар $",
       "euro" => "Евро €",
       "hrn" => "Гривна ₴",
       "cashless" => "Безнал руб. ₽",
    ];

    public function __construct()
    {
        $this->db_ads = $this->getLatest();
    }

    public function getExchangeDirections ( $sell_buy, $currency )
    {
        $query = '_';
        if( $sell_buy == 'sell' || $sell_buy == 'buy'){
            $query = $sell_buy . $query;
        }
        if( array_key_exists( $currency, $this->currencies) ){
            $query .= $currency;
        }
        return Ads::where('type', 'like', "%" . $query . "%")
                  ->orderBy('date', 'desc')->take(100)->get();
    }

    public static function getLatest( $asc_desc = 'desc' ){
        return Ads::orderBy('date', $asc_desc)->take(100)->get();
    }

    public function getPath(){
        $url = URL::current();
        $path = parse_url($url);
        $path_parts = [ "sell_buy" => "all", "currency" => "" ];
        if( !empty($path["path"]) ){
            $path_array = explode("/", $path["path"]);
            $path_parts["sell_buy"] =  $path_array[2];
            $path_parts["currency"] = empty($path_array[3]) ? '' : $path_array[3];
        }
        return $path_parts;
    }

    public function show( $sell_buy = "all", $currency = '' )
    {
        return view('currency', [
            'ads' => $this->getExchangeDirections($sell_buy, $currency),
            'currencies' => $this->currencies,
            'path' => $this->getPath()
        ]);
    }

    public function index()
    {
        return view('currency', [
            // 'ads' => $this->db_ads,
            'ads' => GetAdsController::getPosts( "-87785879" ),
            'currencies' => $this->currencies,
            'path' => $this->getPath()
        ]);
    }
}