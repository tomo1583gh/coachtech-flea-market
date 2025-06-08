<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class TopController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // 検索キーワードがある場合は部分一致検索
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', '%' . $keyword . '%');
        }

        // ログインしていれば自分の商品を除外
        if (Auth::check()) {
            $query->where('user_id', '<>', Auth::id());
        }

        // 1ページ8件 + 検索条件の保持
        $products = $query->paginate(8)->appends($request->all());

        return view('top', compact('products'));
    }

    public function mylist(Request $request)
    {
        $user = auth()->user();
        $favorites = $user->favorites()
            ->when($request->filled('keyword'),function ($query) use ($request) {
                $query->where('name','like', '%' . $request->keyword . '%');
            })
            ->get();

        // ページネーションの処理を手動で行う
        $currentPage = request()->get('page', 1);
        $perPage = 8;
        $currentItems = $favorites->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator($currentItems, $favorites->count(), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);

        return view('top', ['products' => $paginated]);
    }
}
