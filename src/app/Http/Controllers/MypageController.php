<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class MypageController extends Controller
{
    public function sell(Request $request)
    {
        $user = Auth::user();

        if ($request->page === 'buy') {
            // 購入した商品（buyer_id を使う設計）
            $products = Product::where('buyer_id', $user->id)->latest()->paginate(8);
        } else {
            // 出品した商品（user_id を使う設計）
            $products = Product::where('user_id', $user->id)->latest()->paginate(8);
        }

        return view('mypage', compact('products'));
    }

    public function buy()
    {
        $user = Auth::user();

        $products = Product::where('buyr_id', $user->id)->latest()->padinate(8);

        return view('mypage.buy', compact('products'));
    }
}
