<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create()
    {
        return view('sell');
    }

    public function store(Request $request)
    {
        return redirect()->route('top')->with('message', '出品が完了しました');
    }

    public function show($item_id)
    {
        // 仮データ（実際はDBから取得）
        $product = (object)[
            'name' => 'サンプル商品',
            'brand' => 'ブランド名',
            'price' => 47000,
            'description' => '商品の説明文...',
            'categories' => ['家電', 'メンズ'],
            'condition' => '良好',
        ];

        return view('product.show', compact('product'));
    }
}
