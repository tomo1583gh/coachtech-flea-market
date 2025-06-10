<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\User;
use App\Http\Requests\PurchaseRequest;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Illuminate\Support\Facades\Config;

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
    public function store(PurchaseRequest $request, $item_id)
    {
        // 商品の取得
        $product = Product::findOrFail($item_id);

        // 購入処理：購入者IDを保存
        $product->buyer_id = auth::id();
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

    public function checkout(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $product = Product::findOrFail($request->item_id);

        $session = Session::create([
            'payment_method_types' => ['card'], // コンビニは日本だと別オプション
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price, // 47000など（単位：円 → x100 = 4700000）
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('checkout.success'), // 決済成功後のURL
            'cancel_url' => route('checkout.cancel'),   // キャンセル時のURL
        ]);

        return redirect($session->url);
    }
}
