<?php

use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProductController;

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

    // プロフィール画面
    Route::get('/mypage/profile',[ProfileController::class, 'edit'])->name('profile');
    Route::post('/mypage/profile',[ProfileController::class,'update'])->name('profile.update');

    Route::get('/sell', function () {
        return view('sell'); // resources/views/sell.blade.php を表示
    })->name('sell');

    // トップ画面
    Route::get('/', [TopController::class,'index'])->name('top');
    Route::get('/mylist', [TopController::class, 'mylist'])->name('top.mylist');

    // マイページ画面
    Route::get('/mypage', [MypageController::class, 'sell'])->name('mypage.sell');
    Route::get('/mypage?page=buy',[MypageController::class, 'buy'])->name('mypage.buy');

    // 商品出品画面
    Route::get('/sell',[ProductController::class, 'create'])->name('sell');
    Route::post('/sell', [ProductController::class, 'store'])->name('product.store');

    // 商品詳細画面
    Route::get('/item/{item_id}', [ProductController::class, 'show'])->name('product.show');
});