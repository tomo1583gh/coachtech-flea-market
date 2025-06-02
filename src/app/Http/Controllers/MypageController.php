<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPUnit\Framework\MockObject\ReturnValueNotConfiguredException;

class MypageController extends Controller
{
    public function sell()
    {
        $products = collect([
            (object)['name' => '出品商品A'],
            (object)['name' => '出品商品B'],
        ]);

        return view('mypage', compact('products'));
    }

    public function buy()
    {
        $products = collect([
            (object)['name' => '購入商品A'],
            (object)['name' => '購入商品B'],
        ]);

        return view('mypage', compact('products'));
    }
}
