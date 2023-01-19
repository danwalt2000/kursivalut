<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Log;
use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\ParseAdsController;
use App\Http\Controllers\ParseUriController;
use App\Http\Controllers\DBController;
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
    public $to_view = [];
    public $posts;
    public $parsed_url = [];
    public $path = [];
    public $query = '';
    
    public static $publics = [
        "obmenvalut_donetsk"    => ["id" => "-87785879",  "time" => "everyFiveMinutes"],    // 5
        "obmen_valut_donetsk"   => ["id" => "-92215147",  "time" => "everyFiveMinutes"],    // 5
        "obmenvalyut_dpr"       => ["id" => "-153734109", "time" => "everyThirtyMinutes"],  // 30
        "club156050748"         => ["id" => "-156050748", "time" => "everyThirtyMinutes"],  // 30
        "kursvalut_donetsk"     => ["id" => "-63859238",  "time" => "everyThirtyMinutes"],  // 30
        "obmen_valut_dnr"       => ["id" => "-193547744", "time" => "hourly"],              // 60
        "donetsk_obmen_valyuta" => ["id" => "-174075254", "time" => "hourly"],              // 60
        "obmenvalut_dnr"        => ["id" => "-172375183", "time" => "hourly"],              // 60
        "valutoobmen"           => ["id" => "-75586957",  "time" => "hourly"]               // 60
    ];
    public $currencies = [
       "dollar" => "Доллар",
       "euro" => "Евро",
       "hrn" => "Гривна",
       "cashless" => "Безнал руб."
    ];
    public $date_sort = [
        1   => "1 час",
        5   => "5 часов",
        24  => "24 часа",
        168 => "7 дней",
        720 => "30 дней"
    ];

    public function __construct()
    {
        $this->posts = new DBController;
        $this->db_ads = $this->posts->getPosts();
        $this->path = ParseUriController::parseUri();
        if( !empty($this->path['query']) ) $this->query = "?" . $this->path['query'];
        $this->to_view = [
            'ads'             => $this->db_ads,
            'ads_count'       => $this->posts->getPosts("count"),
            'currencies'      => $this->currencies,
            'date_sort'       => $this->date_sort,
            'path'            => $this->path,
            'query'           => $this->query,
            // 'hash'            => $this->getCurrentGitCommit(),
            'h1'              => ParseUriController::getH1(),
            'search'          => '',
            'is_allowed'      => true,
            'submit_msg'      => 'Вы уже публиковали объявление.',
            'next_submit'     => ''
        ];
        $this->middleware(function ($request, $next){
            $this->to_view["is_allowed"] = SessionController::isAllowed();
            $this->to_view["next_submit"] = SessionController::nextSubmit();
            return $next($request);
        });
    }

    // function getCurrentGitCommit( $branch='master' ) {
    //     if ( $hash = file_get_contents( sprintf( '../../.git/refs/heads/%s', $branch ) ) ) {
    //         return trim($hash);
    //     } else {
    //         return false;
    //     }
    // }

    public function show( $sell_buy = "all", $currency = '' )
    {
        $this->to_view['ads'] = $this->posts->getPosts( "get", $sell_buy, $currency );
        $this->to_view['ads_count'] = $this->posts->getPosts("count", $sell_buy, $currency);
        return view('currency', $this->to_view);
    }
    
    public function store( Request $request )
    {
        if ( SessionController::isAllowed() && $request->path() == "all" )  {
            $input = $request->all();
            $validated = $request->validate([
                'sellbuy'   => 'required', 
                'currency'  => 'required',
                'rate'      => 'required|numeric',
                'phone'     => 'required',
                'textarea'   => 'required|max:400',
            ]);

            $currency = array_search($validated["currency"], $this->currencies);
            $type = $validated["sellbuy"] . "_" . $currency;
            
            $id = time();
            $phones_parsed = ParseAdsController::parsePhone( $validated["textarea"], $id );
    
            $args = [
                'vk_id'           => $id,
                'vk_user'         => 0,
                'owner_id'        => 1,
                'date'            => time(),
                'content'         => $validated["textarea"],
                'content_changed' => $phones_parsed["text"],
                'phone'           => $validated["phone"],
                'rate'            => $validated["rate"],
                'phone_showed'    => 0,
                'link_followed'   => 0,
                'popularity'      => 1,
                'link'            => '',
                'type'            => $type
            ];
            DBController::storePosts($args);
            $this->to_view['submit_msg'] = "Ваше объявление опубликовано!";
            SessionController::updateAllowed();
        }

        $this->to_view["ads"] = DBController::getPosts();;
        $this->to_view["is_allowed"] = SessionController::isAllowed();
        $this->to_view["next_submit"] = SessionController::nextSubmit();
        return view('all', $this->to_view);
    }
    
    public function search()
    {
        $search = '';
        if( !empty($_GET["search"]) ){
            $search = $_GET["search"];
        }
        $this->to_view['search'] = $search;
        $this->to_view['ads'] = $this->posts->getPosts( "get", "all", "", $search );
        $this->to_view['ads_count'] = $this->posts->getPosts( "count", "all", "", $search );
        return view('search', $this->to_view);
    }

    public function index()
    {
        $this->to_view['ads'] = GetAdsController::getNewAds( "-87785879" );
        // $this->to_view['ads'] = GetAdsController::getNewAds( "-92215147" );
        return view('currency', $this->to_view);
    }
}