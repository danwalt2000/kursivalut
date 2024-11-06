<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
use Illuminate\Support\Facades\App;
use danielme85\Geoip2\Facade\Reader;
use Log;
 
class SessionController extends Controller
{
    public static function isAllowed() {
        $lastSubmit = session()->get('last_submit');
        $is_allowed = true;
        if (!empty($lastSubmit) ){
            $is_allowed = !($lastSubmit > (time() - 10 * 60));
        }
        return $is_allowed;
     }
     
     public static function updateAllowed() {
        session()->put('last_submit', time());
     }

     public static function nextSubmit(){
        $time_to_next_submit = session()->get('last_submit') + 10 * 60 - time() ;
        return date('i мин. s сек.', $time_to_next_submit);
    }

    public static function getGeodata(){
        $geo_country = session()->get('geo_country');
        $geo_allowed = session()->get('geo_allowed');

        
        if( empty($geo_country) || empty($geo_country) ){
            $geo_country = SessionController::getGeoIp();

            // пользователям из России и Украины ленту не показывать
            $geo_allowed = !boolval(preg_match('/(UA|RU)/', $geo_country));

            session()->put( 'geo_country', $geo_country );
            session()->put( 'geo_allowed', $geo_allowed );
        }
        // var_dump($geo_country);
        // var_dump($geo_allowed);
        // var_dump(preg_match('/(UA|RU)/', $geo_country));
        return [
            'geo_country' => $geo_country,
            'geo_allowed' => $geo_allowed,
        ];
    }

    public static function getGeoIp(){
        $geodata = ['country' => ['iso_code' => 'DE']];
        $reader = Reader::connect();
        if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
            $ip = $_SERVER["HTTP_CF_CONNECTING_IP"];
        } elseif($_SERVER['REMOTE_ADDR']){
            $ip = $_SERVER['REMOTE_ADDR'];
        } else{
            $ip = "51.38.115.34";
        }
        
        // для тестирования
        if(App::environment('local')){
            $ip = env('TEST_IP_LOCATION');
        }

        try {
            $geodata = $reader->city($ip)->jsonSerialize(); //jsonSerialize seems to actually return an associative array.
        }
        catch (\Exception $e) {
            Log::warning($e->getMessage());
            return response()->json("Geo-location not found!", 500);
        }
        return $geodata['country']['iso_code'];
    }


    public static function getHost(){
        $current_domain = 'kursivalut';
        $current_full_host = 'kursivalut';
        
        if( !empty( $_SERVER['SERVER_NAME'] ) ){
            $current_domain = $_SERVER['SERVER_NAME']; 
            $current_full_host = $_SERVER['HTTP_HOST'];
        }
        // var_dump($current_domain);
        // var_dump($current_full_host);
        
        $domain = 'kursivalut';
        $table = 'donetsk';
        
        if( $current_domain != $current_full_host || $current_domain == 'lugansk.kursivalut.ru'){
            $table = explode('.', $current_full_host)[0];
        }

        return [ 'domain' => $domain, 'table' => $table ];
    }
}