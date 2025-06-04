<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

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
        $product = Product::with(['categories','comments.user'])->findOrFail($item_id);
        return view('product.show', compact('product'));
    }
}
