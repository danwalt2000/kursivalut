<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CurrencyController;

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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::resource('/', CurrencyController::class);
Route::get('/ads/{sellbuy}/{currency?}', 
     [CurrencyController::class, 'show'])
     ->where(['sellbuy' => 'sell|buy|all', 'currency' => 'dollar|euro|hrn|cashless']);
