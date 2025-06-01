@extends('layouts.app-auth')

@section('content')
<div class="auth-container">
    <h2>メール認証をしてください</h2>
    <p>確認メールを送信しました。メールに記載されたリンクをクリックして認証を完了してください。</p>

    @if (session('status') == 'verification-link-sent')
    <p style="color: green;">新しい確認メールを送信しました。</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="btn-red">確認メールを再送信</button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-red" style="margin-top: 20px;">ログアウト</button>
    </form>
</div>
@endsection