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
        $userId = Auth::id();

        if ($request->page === 'mylist') {
            if (Auth::check()) {
                $favoriteIds = Auth::user()->favorites()->pluck('products.id');

                $products = Product::whereIn('id', $favoriteIds)
                    ->when($request->filled('keyword'), function ($query) use ($request) {
                        $query->where('name', 'like', '%' . $request->keyword . '%');
                    })
                    ->paginate(8);
            } else {
                $products = collect();
            }
        } else {
            $products = Product::when($userId, function ($query) use ($userId) {
                return $query->where('user_id', '!=', $userId);
            })
                ->when($request->filled('keyword'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->keyword . '%');
                })
                ->paginate(8);
        }

        return view('top', compact('products'));
    }
}
