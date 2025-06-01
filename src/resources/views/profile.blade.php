@extends('layouts.app')

@section('content')
<div class="profile-container">
    <h2 class="profile-title">プロフィール設定</h2>

    <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
        @csrf
        @method('POST')

        <div class="profile-image-area">
            <img src="{{ asset('images/default-avatar.png') }}" class="profile-avatar" alt="プロフィール画像">
            <label class="image-select-button">
                画像を選択する
                <input type="file" name="image" style="display: none;">
            </label>
        </div>

        <div class="form-group">
            <label for="name">ユーザー名</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}">
        </div>

        <div class="form-group">
            <label for="zipcode">郵便番号</label>
            <input id="zipcode" type="text" name="zipcode" value="{{ old('zipcode') }}">
        </div>

        <div class="form-group">
            <label for="address">住所</label>
            <input id="address" type="text" name="address" value="{{ old('address') }}">
        </div>

        <div class="form-group">
            <label for="building">建物名</label>
            <input id="building" type="text" name="building" value="{{ old('building') }}">
        </div>

        <button type="submit" class="btn-red">更新する</button>
    </form>
</div>
@endsection