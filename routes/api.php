<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RatesController;
use App\Http\Controllers\GetAdsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/rates', function (Request $request) {
//     return $request->user();
// });

Route::get('/rates', [RatesController::class, 'getAll']);

Route::get('/posts/add', function () { return redirect('/'); });
Route::post('/posts/add', [GetAdsController::class, 'getNewAdByAPI']);