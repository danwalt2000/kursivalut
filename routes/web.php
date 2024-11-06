<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\AjaxController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::resource('/', CurrencyController::class);
Route::redirect('/ads', '/', 301);
Route::get('/ads/{sellbuy}/{currency?}', 
     [CurrencyController::class, 'show'])
     ->where(['sellbuy' => 'sell|buy|all', 'currency' => 'dollar|euro|hrn']);
     
Route::redirect('/ads/{sellbuy}/cashless', '/', 301)->where(['sellbuy' => 'sell|buy|all']);

Route::post('/all', [CurrencyController::class, 'store']);
Route::get('/all', function () { return redirect('/'); });

Route::get('/s', [CurrencyController::class, 'search']);

Route::get('/ajax', [AjaxController::class, 'ajax']);
Route::post('/ajax', [AjaxController::class, 'ajaxPost']);

Route::get('/{landing}', [CurrencyController::class, 'landing'])
     ->where(['landing' => 'legal']);

Route::get("sitemap.xml" , [CurrencyController::class, 'sitemap']);