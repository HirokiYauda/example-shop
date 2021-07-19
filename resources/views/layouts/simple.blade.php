<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name=”robots” content=”noindex,nofollow” />
    <title>@yield('title', 'TOP') | Example Shop</title>
    <meta name="description" content="@yield('description')">
    @yield('canonical')
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @yield('add_script')
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-PQD6Y2F4PB"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-PQD6Y2F4PB');
    </script>
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="{{ route('top') }}">
                    {{ config('app.name') }}
                </a>
            </div>
        </nav>

        <main class="py-4 container">
            @yield('content')
        </main>

        <a href="javascript:void(0);" id="js-scroll-top" class="scroll-top text-decoration-none bg-secondary d-block py-3 scroll-top text-center text-light">トップへ戻る</a>
        <footer class="bg-white py-5 text-center">
            <p class="lead">{{ config('app.name') }}</p>
            <p>&copy; 2021, Example Shop</p>
        </footer>

        
    </div>
</body>

</html>