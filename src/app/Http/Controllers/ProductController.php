<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;
use App\Models\Category;

class ProductController extends Controller
{
    public function create()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(StoreProductRequest $request)
    {
        $product = new Product();

        // 画像の保存処理を追加
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('products', 'public');
            $product->image_path = 'storage/' . $path;
        }

        // フォームからの情報を代入
        $product->user_id = auth()->id(); // 出品者
        $product->fill($request->only(['name', 'brand', 'description', 'price', 'condition']));
        $product->save();

        // カテゴリーの中間テーブル登録
        $product->categories()->sync($request->categories);

        return redirect()->route('top')->with('status', '商品を出品しました。');
    }

    public function show($item_id)
    {
        $product = Product::with(['categories', 'comments.user'])
            ->withCount(['comments'])
            ->findOrFail($item_id);

        return view('product.show', compact('product'));
    }
}
