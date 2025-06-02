@extends('layouts.app')

@section('content')
<div class="product-detail-container">
    {{-- 左：商品画像 --}}
    <div class="product-image-area">
        <div class="product-image">商品画像</div>
    </div>

    {{-- 右：商品情報 --}}
    <div class="product-info-area">
        <h2 class="product-title">商品名がここに入る</h2>
        <p class="product-brand">ブランド名</p>
        <p class="product-price">￥47,000 <span>（税込）</span></p>

        <div class="product-actions">
            <div class="icon-row">
                <span class="icon">★</span> <span>3</span>
                <span class="icon">💬</span> <span>1</span>
            </div>
            <a href="#" class="btn-red">購入手続きへ</a>
        </div>

        <div class="product-description">
            <h3>商品説明</h3>
            <p>カラー：グレー</p>
            <p>商品の状態は良好です。傷もありません。<br>購入後、即発送いたします。</p>
        </div>

        <div class="product-meta">
            <h3>商品の情報</h3>
            <p>カテゴリー：
                <span class="tag">家電</span>
                <span class="tag">メンズ</span>
            </p>
            <p>商品の状態：<span>良好</span></p>
        </div>

        <div class="product-comments">
            <h3>コメント (1)</h3>
            <div class="comment-item">
                <div class="avatar"></div>
                <div class="comment-body">
                    <strong>admin</strong>
                    <div class="comment-text">こちらにコメントがあります。</div>
                </div>
            </div>

            <form class="comment-form">
                <label for="comment">商品へのコメント</label>
                <textarea name="comment" id="comment" rows="4" placeholder="コメントを入力してください"></textarea>
                <button type="submit" class="btn-red">コメントを送信する</button>
            </form>
        </div>
    </div>
</div>
@endsection