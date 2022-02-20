<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// getの場合の処理を書く：getは単にアクセスしてビューを表示する場合
Route::get('/home', [HomeController::class, 'index'])->name('home');
// POSTの場合の処理を書く：postはformから送信する場合
Route::post('/store', [HomeController::class, 'store'])->name('store');
