@extends('layouts.app')

@section('content')
<div class="mypage-container">

    <div class="mypage-profile">
        <img src="{{ Auth::user()->image_path ? asset('storage/' . Auth::user()->image_path) : asset('images/default-avatar.png') }}" class="mypage-avatar" alt="ユーザー画像">

        <div class="mypage-info">
            <h2>{{ Auth::user()->name }}</h2>
            <a href="{{ route('profile') }}" class="edit-profile-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage-tabs">
        <a href="{{ route('mypage', ['page' => 'sell']) }}" class="{{ request('page') !== 'buy' ? 'tab active' : 'tab' }}">出品した商品</a>
        <a href="{{ route('mypage', ['page' => 'buy']) }}" class="{{ request('page') === 'buy' ? 'tab active' : 'tab' }}">購入した商品</a>
    </div>

    <div class="product-list">
        @if ($products->isEmpty())
        @if (request('page') === 'buy')
        <p class="no-product-message">購入した商品はありません。</p>
        @else
        <p class="no-product-message">出品した商品はありません。</p>
        @endif
        @else
        @foreach ($products as $product)
        <a href="{{ route('product.show', ['item_id' => $product->id]) }}" class="product-card">
            <div class="product-image-wrapper">
                <img src="{{ asset($product->image_path ?? 'image/no-image.png') }}" alt="{{ $product->name }}" class="product-image">
                @if ($product->is_sold === true || $product->is_sold === 1)
                <div class="sold-label">SOLD</div>
                @endif
            </div>
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-price">￥{{ number_format($product->price) }}</div>
        </a>
        @endforeach
        @endif
    </div>

    <div class="pagination-wrapper">
        {{ $products->links() }}
    </div>

</div>
@endsection