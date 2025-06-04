<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TopController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CommentController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


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

// トップページ　(商品一覧）　※ログイン不要
Route::get('/', [TopController::class,'index'])->name('top');
Route::get('/mylist', [TopController::class,'mylist'])->name('top.mylist');

// 商品詳細画面　※ログイン不要
Route::get('/item/{item_id}', [ProductController::class,'show'])->name('product.show');

// コメント
Route::post('/item/{item_id}/comment', [CommentController::class, 'store'])->name('comment.store');

// 検索機能
Route::get('/search', [SearchController::class, 'index'])->name('search');

// メール認証リンクからのアクセス処理
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // 認証完了としてマークする
    return redirect('/mypage/profile'); // 認証後のリダイレクト先（プロフィール設定）
})->middleware(['auth', 'signed'])->name('verification.verify');

// ログイン必須のページ
Route::middleware(['auth', 'verified'])->group(function () {
    // プロフィール
    Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profile');
    Route::post('/mypage/profile', [ProfileController::class, 'update'])->name('profile.update');

    // マイページ(出品/購入)
    Route::get('/mypage', [MypageController::class, 'sell'])->name('mypage.sell');
    Route::get('/mypage?page=buy', [MypageController::class, 'buy'])->name('mypage.buy');

    // 商品出品
    Route::get('/sell', [ProductController::class, 'create'])->name('sell');
    Route::post('/sell', [ProductController::class,'store'])->name('product.store');
});