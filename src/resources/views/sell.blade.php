@extends('layouts.app')

@section('content')
<div class="sell-container">
    <h2 class="sell-title">商品の出品</h2>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image">商品画像</label>
            <input type="file" name="image" id="image" class="upload-input" accept="image/*">
            @error('image')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- カテゴリー --}}
        <h3>カテゴリー</h3>
        <div class="category-area">
            @foreach ($categories as $category)
            <label class="category-tag">
                <input type="checkbox" name="category_id[]" value="{{ $category->id }}"
                    {{ in_array($category->id, old('categories', [])) ? 'checked' : '' }}>
                {{ $category->name }}
            </label>
            @endforeach
        </div>
        @error('categories')
        <p class="error">{{ $message }}</p>
        @enderror

        {{-- 商品の状態 --}}
        <div class="form-group">
            <label for="state">商品の状態</label>
            <select name="state" id="state">
                <option value="">選択してください</option>
                <option value="新品・未使用">新品・未使用</option>
                <option value="未使用に近い">未使用に近い</option>
                <option value="目立った傷や汚れなし">目立った傷や汚れなし</option>
                <option value="やや傷や汚れあり">やや傷や汚れあり</option>
                <option value="傷や汚れあり">傷や汚れあり</option>
            </select>
        </div>

        {{-- 商品名・説明・価格 --}}
        <div class="form-group">
            <label for="name">商品名</label>
            <input type="text" name="name" id="name">
        </div>

        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand">
        </div>

        <div class="form-group">
            <label for="description">商品の説明</label>
            <textarea name="description" id="description" rows="4"></textarea>
        </div>

        <div class="form-group">
            <label for="price">販売価格</label>
            <input type="number" name="price" id="price" placeholder="￥">
        </div>

        <button type="submit" class="btn-red">出品する</button>
    </form>
</div>
@endsection