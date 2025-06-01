<?php

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;

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

Route::get('/search', [SearchController::class, 'index'])->name('search');

// Fortify認証後のHOMEに使う(RouteServiceProvider::HOMEに対応)
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/mypage/profile',[ProfileController::class, 'edit'])->name('profile');
    Route::post('/mypage/profile',[ProfileController::class,'update'])->name('profile.update');
    Route::get('/sell', function () {
        return view('sell'); // resources/views/sell.blade.php を表示
    })->name('sell');
});