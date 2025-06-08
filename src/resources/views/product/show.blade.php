@extends('layouts.app')

@section('content')
<div class="product-detail-container">
    {{-- 左：商品画像 --}}
    <div class="product-image-area">
        <div class="product-image-show">
            <img src="{{ asset($product->image_path ?? 'image/no-image.png') }}" alt="{{ $product->name }}">
        </div>
    </div>

    {{-- 右：商品情報 --}}
    <div class="product-info-area">
        <h2 class="product-title">{{ $product->name }}</h2>
        <p class="product-brand">{{ $product->brand ?? 'ブランド名未設定' }}</p>
        <p class="product-price">￥{{ number_format($product->price) }} <span>（税込）</span></p>

        <div class="product-actions">
            <div class="icon-row">
                {{-- いいねアイコン --}}
                @auth
                <form method="POST" action="{{ route('favorite.toggle', ['item_id' => $product->id]) }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="like-button">
                        @if (auth()->user()->favorites->contains($product->id))
                        ❤️
                        @else
                        🤍
                        @endif
                        {{ $product->likedUsers->count() }}
                    </button>
                </form>
                @else
                <span class="icon">🤍</span> <span>{{ $product->likedUsers->count() }}</span>
                @endauth

                {{-- コメント数 --}}
                <span class="icon">💬</span> <span>{{ $product->comments_count ?? 0 }}</span>
            </div>

            <a href="{{ route('purchase.show', ['item_id' => $product->id]) }}" class="btn-red">購入手続きへ</a>
        </div>

        <div class="product-description">
            <h3>商品説明</h3>
            <p>{{ $product->description }}</p>
        </div>

        <div class="product-meta">
            <h3>商品の情報</h3>
            <p>カテゴリー：
                @if (!empty($product->categories))
                @foreach ($product->categories as $category)
                <span class="tag">{{ $category->name }}</span>
                @endforeach
                @else
                <span class="tag">未設定</span>
                @endif
            </p>
            <p>商品の状態：<span>{{ $product->condition ?? '未設定' }}</span></p>
        </div>

        <div class="product-comments">
            <h3>コメント ({{ count($product->comments ?? []) }})</h3>
            @if (!empty($product->comments))
            @foreach ($product->comments as $comment)
            <div class="comment-item">
                <div class="avatar"></div>
                <div class="comment-body">
                    <strong>{{ $comment->user->name }}</strong>
                    <div class="comment-text">{{ $comment->body }}</div>
                </div>
            </div>
            @endforeach
            @else
            <p>まだコメントはありません。</p>
            @endif

            @auth
            <form method="POST" action="{{ route('comment.store', ['item_id' => $product->id]) }}" class="comment-form">
                @csrf
                <label for="comment">商品へのコメント</label>
                <textarea name="comment" id="comment" rows="4" placeholder="コメントを入力してください"></textarea>
                <button type="submit" class="btn-red">コメントを送信する</button>
            </form>
            @else
            <p><a href="{{ route('login') }}">ログイン</a>してコメント出来ます。</p>
            @endauth
        </div>
    </div>
</div>

@endsection