<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>認証画面</title>
    <link rel="stylesheet" href="{{ asset('css/style-auth.css') }}">
</head>

<body>
    <header class="auth-header">
        <img src="{{ asset('images/logo.svg') }}" alt="ロゴ" class="logo">
    </header>
    <main>
        @yield('content')
    </main>
</body>

</html>