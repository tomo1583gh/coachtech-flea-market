<?php

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

// トップペｰジ(ログイン前向け)
Route::get('/', function () {
    return redirect('/login'); // 未ログインならログインへ
});

// Fortify認証後のHOMEに使う(RouteServiceProvider::HOMEに対応)
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/mypage/profile', function () {
        return view('profile'); // resources/view/profile.blade.phpを表示
    })->name('profile');

});