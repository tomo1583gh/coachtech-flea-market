<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CommentRequest;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $item_id)
    {
        Comment::create([
            'product_id' => $item_id,
            'user_id' => Auth::id(),
            'body' => $request->input('comment'),
        ]);

        return redirect()->route('product.show', ['item_id' => $item_id])
                        ->with('status', 'コメントを投稿しました。');
    }
}
