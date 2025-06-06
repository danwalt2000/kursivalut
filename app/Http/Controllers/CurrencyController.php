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
use App\Http\Controllers\RatesController;
 
class CurrencyController extends Controller
{
    public $db_ads = [];     // последние записи
    public $to_view = [];    // массив переменных для представления
    public $host;            // текущий домен и поддомен
    public $locale;          // текущая локаль из конф. файла locales.php
    public $currencies = []; // список валют для текущей локали
    public $currencies_loc = []; // список валют в родительном падеже
    public $path = [];       // разложенный по частям uri
    public $table;           // текущая таблица БД, например, donetsk 
    public $query = '';
    public $rates;

    public function __construct()
    {
        $this->host = SessionController::getHost();
        // $this->geodata = SessionController::getGeodata();
        
        $this->table = $this->host['table'] ?? "donetsk";
        $this->locales = Config::get('locales'); 
        $this->rates = new RatesController;
        $this->currencies_loc = Config::get('common.currencies_loc');
        $this->path = ParseUriController::parseUri();

        // в разных локалях разные наборы валют
        $this->locale = Config::get('locales.' . $this->host['table']);
        if(empty($this->locale['currencies']) || 
            (!empty($this->path["currency"]) && !in_array($this->path["currency"], $this->locale['currencies'])) ) return abort(404);

        foreach( $this->locale['currencies'] as $currency ){
            $this->currencies[$currency] = Config::get('common.currencies')[$currency];
        }

        $metrika_id = $this->locale['metrika'] ?? env("METRIKA_ID");

        $this->db_ads = DBController::getPosts( $this->table );
        $last_ad_time = isset($this->db_ads->first()->date) ? $this->db_ads->first()->date : '';
        
        if( !empty($this->path['query']) ) $this->query = "?" . $this->path['query'];
        $this->to_view = [
            'ads'             => $this->db_ads,
            'last_ad_time'    => $last_ad_time,
            'ads_count'       => DBController::getPosts($this->table, "count"),
            'currencies'      => $this->currencies,
            'currencies_loc'  => $this->currencies_loc,
            'locales'         => $this->locales,
            'locale'          => $this->locale,
            'table'           => $this->table,
            'date_sort'       => Config::get('common.date_sort'),
            'path'            => $this->path,
            'query'           => $this->query,
            'geodata'         => once(fn()=> SessionController::getGeodata() ),
            'rates'           => $this->rates->getRatesByLocale( $this->locale ),
            'stock_rates'     => $this->rates->getRatesByLocale( 'stock' ),
            'title'           => ParseUriController::generateTitle(),
            'hash'            => $this->getCurrentGitCommit(),
            'h1'              => ParseUriController::getH1(),
            'metrika'         => $metrika_id,
            'search'          => '',
            'add_class'       => '',
            'is_allowed'      => true,
            'submit_msg'      => 'Вы уже публиковали объявление.',
            'next_submit'     => ''
        ];
        // middleware для записи в сессию времени публикации объявления
        // и возможности нового заполнения формы
        $this->middleware(function ($request, $next){
            $this->to_view["is_allowed"]  = SessionController::isAllowed();
            $this->to_view["next_submit"] = SessionController::nextSubmit();
            // $this->to_view["geodata"]     = SessionController::getGeodata();
            return $next($request);
        });
    }

    // используется для добавления версии к css и js файлам
    function getCurrentGitCommit( $branch='master' ) 
    {
        if ( $hash = file_get_contents( sprintf( __DIR__ . '/../../../.git/refs/heads/%s', $branch ) ) ) {
            return trim($hash);
        } else {
            return false;
        }
    }

    // фильтры: покупка/продажа, валюта
    public function show( $sell_buy = "all", $currency = '' )
    {
        $this->to_view['ads'] = DBController::getPosts( $this->table, "get", $sell_buy, $currency );
        $this->to_view['ads_count'] = DBController::getPosts( $this->table, "count", $sell_buy, $currency);
        return view('currency', $this->to_view);
    }
    
    // добавления нового объявления через форму
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

            $currency = array_search( $validated["currency"], Config::get('common.currencies') );
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
            DBController::storePosts( $this->table, $args);
            $this->to_view['submit_msg'] = "Ваше объявление опубликовано!";
            SessionController::updateAllowed();
        }

        $this->to_view["ads"] = DBController::getPosts($this->table);;
        $this->to_view["is_allowed"] = SessionController::isAllowed();
        // $this->to_view["next_submit"] = SessionController::nextSubmit();
        return view('all', $this->to_view);
    }
    
    // страница поиска
    public function search()
    {
        $search = $_GET["search"] ?? '';
        
        $this->to_view['search'] = $search;
        $this->to_view['ads'] = DBController::getPosts( $this->table, "get", "all", "", $search );
        $this->to_view['ads_count'] = DBController::getPosts( $this->table, "count", "all", "", $search );
        $this->to_view['add_class'] = 'page-search';

        return view('search', $this->to_view);
    }

    // отдельный контроллер и шаблон для лендингов
    public function landing()
    {
        $path = explode( "?", \Request::getRequestUri() )[0];
        return view($path, $this->to_view);
    }
    // отдельный контроллер для сайтмапов
    public function sitemap()
    {
        return \Illuminate\Support\Facades\Redirect::to('/sitemaps/sitemap-' . $this->table . '.xml');
    }

    // главная страница
    public function index()
    {
        if( !empty($_GET["wall"]) && preg_match('/\d{7,10}\_\d{1,10}/', $_GET["wall"]) ){
            $channel_and_id = explode("_", $_GET["wall"]);
            if(isset($channel_and_id[0]) && is_int((int)$channel_and_id[0]) && isset($channel_and_id[1]) && is_int((int)$channel_and_id[1])){
                $modal_ad = DBController::getPostByChannelAndId($channel_and_id[0], $channel_and_id[1]);
                if(empty($modal_ad)){
                    $modal_ad = [
                        "content_changed" => "Объявление не найдено",
                        "owner_id" => 0,
                        "date" => 0,
                    ];
                }
                $this->to_view['modal_ad'] = $modal_ad;
            }
        }
        return view('currency', $this->to_view);
    }
}