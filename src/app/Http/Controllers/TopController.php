<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class TopController extends Controller
{
    public function index()
    {
        $products = Product::all(); // おすすめ商品取得（仮）

        return view('top', compact('products'));
    }

    public function mylist()
    {
        $products = auth()->user()->favorites ?? collect(); // マイリスト商品取得 (仮)

        return view('top',compact('products'));
    }
}
