@extends('layouts.app-auth')

@section('content')
<div class="auth-container center">
    <p class="message">
        登録していただいたメールアドレスに認証メールを送付しました。<br>
        メール認証を完了してください。
    </p>

    <a href="https://mail.google.com/" target="_blank" class="btn-gray">認証はこちらから</a>

    @if (session('status') == 'verification-link-sent')
    <p class="success-message">確認メールを再送信しました。</p>
    @endif

    <form method="POST" action="{{ route('verification.send') }}">
        @csrf
        <button type="submit" class="resend-link">認証メールを再送する</button>
    </form>
</div>
@endsection