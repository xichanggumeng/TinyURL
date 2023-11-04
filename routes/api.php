<?php

use App\Http\Controllers\TinyURL;
use App\Http\Middleware\ApiKeyAuthMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::middleware(ApiKeyAuthMiddleware::class)->group(static function () {
    Route::any('/set-url',[TinyURL::class,'setUrl']);
    Route::any('/get-url',[TinyURL::class,'getUrl']);
});
