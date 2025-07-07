<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ExhibitionRequest;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        if ($request->page === 'mylist') {
            if (Auth::check()) {
                // ログインしている場合のみ、マイリスト表示
                $products = Auth::user()->favorites()->paginate(8);
            } else {
                // 未ログインは空を返す
                $products = collect();
            }
        } else {
            // 通常の商品一覧（出品者自身のもの以外、未購入）
            $products = Product::where('user_id', '!=', Auth::id())
                ->where('is_sold', false)
                ->paginate(8);
        }

        return view('top', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        // 商品登録
        $product = new Product();
        $product->user_id = auth()->id();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->state = $request->state;

        // 画像の保存処理
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image_Path = $request->file('image')->store('products', 'public');
            $product->image_path = $image_Path;
        }

        $product->save();

        // カテゴリ紐付け（中間テーブル）
        if ($request->has('category_ids')) {
            $product->categories()->sync($request->category_ids);
        }

        return redirect()->route('top')->with('status', '商品を出品しました。');
    }

    public function show($item_id)
    {
        $product = Product::with(['categories', 'comments.user', 'likedUsers'])
            ->withCount(['comments'])
            ->findOrFail($item_id);

        // コメントとユーザー情報をリロード
        $product->load('comments.user');

        return view('product.show', compact('product'));
    }
}
