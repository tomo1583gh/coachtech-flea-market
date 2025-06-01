@extends('layouts.app')

@section('content')
<div class="auth-container">
    <h2>検索結果</h2>
    <p>キーワード: {{ $keyword ?? 'なし' }}</p>
</div>
@endsection