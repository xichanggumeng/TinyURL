<?php

use App\Http\Controllers\TinyURL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', static function () {
    return 'Hello, world!';
});


Route::get('/{key}', [TinyURL::class, 'run'])
    ->where('key', '[A-Za-z0-9]{4}');
