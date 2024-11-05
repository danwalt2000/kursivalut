<?php
 
namespace App\Http\Controllers;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Psr\Http\Message\RequestInterface;
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

    public static function getHost(){
        $current_domain = 'kursivalut';
        $current_full_host = 'kursivalut';
        
        if( !empty( $_SERVER['SERVER_NAME'] ) ){
            $current_domain = $_SERVER['SERVER_NAME']; 
            $current_full_host = $_SERVER['HTTP_HOST'];
        }
        
        $domain = str_contains($current_full_host, 'valuta-dn') ? 'valuta-dn' : 'kursivalut';

        $table = ($domain == 'kursivalut') ? 'donetsk' : 'moscow';
        if( $current_domain != $current_full_host){
            $table = explode('.', $current_full_host)[0];
        }

        return [ 'domain' => $domain, 'table' => $table ];
    }
}