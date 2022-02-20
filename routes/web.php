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
// 更新するデータを表示させる処理を書く
Route::get('/edit/{id}', [HomeController::class, 'edit'])->name('edit');
// 更新する処理を書く
Route::post('/update', [HomeController::class, 'update'])->name('update');
// 削除する処理を書く
Route::post('/destroy', [HomeController::class, 'destroy'])->name('destroy');
