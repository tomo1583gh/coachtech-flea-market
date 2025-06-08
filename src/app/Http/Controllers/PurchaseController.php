<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;

class PurchaseController extends Controller
{
    // 購入ページ表示
    public function show($item_id)
    {
        $product = Product::findOrFail($item_id);
        $user = Auth::user();

        return view('purchase',compact('product', 'user'));
    }

    // 購入処理
    public function store(Request $request, $item_id)
    {
        // 商品の取得
        $product = Product::findOrFail($item_id);

        // 購入処理：購入者IDを保存
        $product->buyer_id = Auth::id();
        $product->is_sold = true;
        $product->save();

        return redirect()->route('mypage.buy')->with('success', '購入が完了しました。');
    }

    public function editAddress($item_id)
    {
        $user = Auth::user();
        $product = Product::findOrFail($item_id);

        return view('address.edit',compact('user', 'product'));
    }

    public function updateAddress(Request $request, $item_id)
    {
        $user = Auth::user();
        $user->zip = $request->zip;
        $user->address = $request->address;
        $user->building = $request->building;
        $user->save();

        return redirect()->route('purchase.show', ['item_id' => $item_id])->with('success', '住所を変更しました。');
    }
}
