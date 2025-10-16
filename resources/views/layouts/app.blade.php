<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Aplikasi')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <nav>
        <!-- Navigasi, bisa tambahkan link logout di sini -->
    </nav>
    <div class="container">
        @yield('content')
    </div>
</body>
</html>