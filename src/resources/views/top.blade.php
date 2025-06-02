@extends('layouts.app')

@section('content')
<div class="tab-area">
    <a href="{{ route('top') }}" class="{{ request()->path() === '/' ? 'tab active' : 'tab' }}">おすすめ</a>
    <a href="{{ route('top.mylist') }}" class="{{ request()->is('mylist') ? 'tab active' : 'tab' }}">マイリスト</a>
</div>

<div class="product-list">
    @foreach ($products as $product)
    <div class="product-card">
        <img src="{{ $product->image_path ?? asset('images/no-image.png') }}" class="product-image">
        <div class="product-name">{{ $product->name }}</div>
        <div class="product-price">￥{{ number_format($product->price) }}</div>
    </div>
    @endforeach
</div>
@endsection