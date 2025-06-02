@extends('layouts.app')

@section('content')
<div class="mypage-container">

    <div class="mypage-profile">
        <img src="{{ asset('images/default-avatar.png') }}" class="mypage-avatar" alt="ユーザー画像">
        <div class="mypage-info">
            <h2>{{ Auth::user()->name }}</h2>
            <a href="{{ route('profile') }}" class="edit-profile-btn">プロフィールを編集</a>
        </div>
    </div>

    <div class="mypage-tabs">
        <a href="{{ route('mypage.sell') }}" class="{{ request('page') !== 'buy' ? 'tab active' : 'tab' }}">出品した商品</a>
        <a href="{{ route('mypage.buy') }}" class="{{ request('page') === 'buy' ? 'tab active' : 'tab' }}">購入した商品</a>
    </div>

    <div class="product-list">
        @foreach ($products as $product)
        <div class="product-card">
            <div class="product-image">商品画像</div>
            <div class="product-name">{{ $product->name }}</div>
        </div>
        @endforeach
    </div>

</div>
@endsection