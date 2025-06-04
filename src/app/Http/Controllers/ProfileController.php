<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile');
    }

    public function update(Request $request)
    {
        // ユーザー取得
        $user = auth()->user();

        // 更新処理
        $user->name = $request->name;
        $user->zipcode = $request->zipcode;
        $user->address = $request->address;
        $user->building = $request->building;

        //　画像処理
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('avatars', 'public');
            $user->image_path = 'storage/' . $path;
        }

        $user->save();

        return redirect()->route('top')->with('status', 'プロフィールを更新しました。');
    }
}
