<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Log;
use Config;
use App\Http\Controllers\GetAdsController;
use App\Http\Controllers\ParseAdsController;
use App\Http\Controllers\ParseUriController;
use App\Http\Controllers\DBController;
 
class CurrencyController extends Controller
{
    public $db_ads = [];
    public $get_posts;
    public $to_view = [];
    public $posts;
    public $host;
    public $locale;
    public $currencies = [];
    public $domain;
    public $table;
    public $parsed_url = [];
    public $path = [];
    public $query = '';

    public function __construct()
    {
        $this->posts = new DBController;
        $this->host = SessionController::getHost();
        $this->domain = $this->host['domain'];
        $this->table = $this->host['table'];

        // в разных локалях разные наборы валют
        $this->locale = Config::get('locales.' . $this->host['table']);
        foreach( $this->locale['currencies'] as $currency ){
            $this->currencies[$currency] = Config::get('common.currencies')[$currency];
        }
        $this->db_ads = $this->posts->getPosts( $this->table );
        $this->path = ParseUriController::parseUri();
        if( !empty($this->path['query']) ) $this->query = "?" . $this->path['query'];
        $this->to_view = [
            'ads'             => $this->db_ads,
            'ads_count'       => $this->posts->getPosts($this->table, "count"),
            'currencies'      => $this->currencies,
            'table'           => $this->table,
            'date_sort'       => Config::get('common.date_sort'),
            'path'            => $this->path,
            'query'           => $this->query,
            'hash'            => $this->getCurrentGitCommit(),
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

    // используется для добавления версии к css файлу
    function getCurrentGitCommit( $branch='master' ) {
        if ( $hash = file_get_contents( sprintf(__DIR__ . '/../../../.git/refs/heads/%s', $branch ) ) ) {
            return trim($hash);
        } else {
            return false;
        }
    }

    public function show( $sell_buy = "all", $currency = '' )
    {
        $this->to_view['ads'] = $this->posts->getPosts( $this->table, "get", $sell_buy, $currency );
        $this->to_view['ads_count'] = $this->posts->getPosts( $this->table, "count", $sell_buy, $currency);
        return view('currency', $this->to_view);
    }
    
    public function store( Request $request )
    {
        if ( SessionController::isAllowed() && $request->path() == "all" )  {
            $input = $request->all();
            $validated = $request->validate([
                'sellbuy'   => 'required', 
                'currency'  => 'required',
                'rate'      => 'required|numeric|max:200',
                'phone'     => 'required',
                'textarea'  => 'required|max:400',
            ]);

            $currency = array_search($validated["currency"], Config::get('common.currencies'));
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
                'popularity'      => 3,  // добавим популярности объявлениям с нашего сайта
                'link'            => '',
                'type'            => $type
            ];
            $this->posts->storePosts( $this->table, $args);
            $this->to_view['submit_msg'] = "Ваше объявление опубликовано!";
            SessionController::updateAllowed();
        }

        $this->to_view["ads"] = DBController::getPosts($this->table);;
        $this->to_view["is_allowed"] = SessionController::isAllowed();
        $this->to_view["next_submit"] = SessionController::nextSubmit();
        return view('all', $this->to_view);
    }
    
    public function search()
    {
        $search = !empty($_GET["search"]) ? $_GET["search"] : '';
        
        $this->to_view['search'] = $search;
        $this->to_view['ads'] = $this->posts->getPosts( $this->table, "get", "all", "", $search );
        $this->to_view['ads_count'] = $this->posts->getPosts( $this->table, "count", "all", "", $search );
        
        return view('search', $this->to_view);
    }

    public function index()
    {
        // $receiver = new GetAdsController;
        // $this->to_view['ads'] = $receiver->getNewAds( $this->vars->publics["obmenvalut_donetsk"] );
        // $this->to_view['ads'] = $receiver->getNewAds( $this->vars->publics["1154050282"] );
        // $this->to_view['ads'] = GetAdsController::getNewAds( "-92215147" );
        return view('currency', $this->to_view);
    }
}