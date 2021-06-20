<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="{{ asset('js/app.js') }}" defer></script>
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white">
            <div class="container">
                <a class="navbar-brand" href="{{ route('top') }}">
                    {{ config('app.name') }}
                </a>

                <div class="dropdown d-md-none">
                    <a id="navbarDropdown" class="dropdown-toggle text-secondary" href="#" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                        {{ Auth::user()->name ?? 'guest' }} <span class="caret"></span>
                    </a>
                    
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                        @guest
                            <a class="dropdown-item" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                            @if (Route::has('register'))
                            <a class="dropdown-item" href="{{ route('register') }}">{{ __('会員登録') }}</a>
                            @endif
                            <a class="dropdown-item" href="{{ route('cart_index') }}">カートを見る</a>
                        @else
                            <a class="dropdown-item" href="{{ route('cart_index') }}">カートを見る</a>
                            <a class="dropdown-item" href="{{ route('mypage_edit') }}">アカウント管理</a>

                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('ログアウト') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        @endguest
                    </div>
                </div>

                {{-- 検索ボックス --}}
                <div class="col-md-7">
                    <form class="form-inline my-2 my-lg-0" action="{{route('search')}}" method="GET" id="global_search_form">
                        <div>
                            <select id="global_search_category" name="category" class="form-control input-lg" id="exampleFormControlSelect1">
                                <option value="">すべて</option>
                                @foreach ($categories as $category)
                                    <option value="{{$category->name_en}}" {{($category_name_en ?? "") === $category->name_en ? 'selected' : ""}}>{{$category->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col pl-1 pr-1">
                            <input id="global_search_freeword" value="{{request('free_word', "")}}" class="form-control mr-sm-2 mr-2 w-100" type="search" name="free_word" placeholder="Search" aria-label="Search">
                        </div>
                        <div class="text-center"><button id="global_search_btn" class="btn btn-outline-success my-2 my-sm-0" type="button">Search</button></div>
                    </form>
                </div>

                <div>
                    @guest
                        <ul class="navbar-nav ml-auto d-none d-md-flex" >
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">{{ __('ログイン') }}</a>
                            </li>
                            @if (Route::has('register'))
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('会員登録') }}</a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('cart_index') }}">カートを見る</a>
                            </li>
                        </ul>
                    @else
                    <div class="dropdown d-none d-md-block">
                        <a id="navbarDropdown" class="dropdown-toggle text-secondary" href="#" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name ?? 'guest' }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('cart_index') }}">カートを見る</a>
                            <a class="dropdown-item" href="{{ route('mypage_edit') }}">アカウント管理</a>

                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('ログアウト') }}
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </div>
                    @endguest
                </div>
            </div>
        </nav>

        <div class="bg-white py-2">
            <ul class="row mb-0 mr-0">
                @if(isset($genres) && !empty($genres))
                    @foreach ($genres as $genre)
                        <li class="list-unstyled">
                            <a  class="btn btn-primary mr-2 font06" href="{{route('genre_narrowing_down', ['category' => $category->name_en, 'genre' => $genre->name_en])}}">{{$genre->name}}</a>
                        </li>
                    @endforeach
                @else
                    @foreach ($categories as $category)
                        <li class="list-unstyled">
                            <a  class="btn btn-primary mr-2 font06" href="{{route('category_narrowing_down', ['category' => $category->name_en])}}">{{$category->name}}</a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </div>

        <main class="py-4 container">
            @yield('content')
        </main>

        <a href="javascript:void(0);" id="js-scroll-top" class="scroll-top text-decoration-none bg-secondary d-block py-3 scroll-top text-center text-light">トップへ戻る</a>
        <footer class="bg-white py-5 text-center">
            <p class="lead">{{ config('app.name') }}</p>
            <p>&copy; 2021, Exapmle Shop</p>
        </footer>

        
    </div>
</body>

</html>