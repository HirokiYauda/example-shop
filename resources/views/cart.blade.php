@extends('layouts.default')
@section('title', 'Exapmle Shop')

@section('content')
<div class="container">
    <h1 class="h3 text-center mb-4">ショッピングカート</h1>
    <div class="row justify-content-between">
        {{-- メインカラム --}}
        <div class="column col-lg-8">
            @if($carts->isNotEmpty())
                @foreach($carts as $cart)
                    <div class="row justify-content-between bg-white px-2 py-3 mb-3">
                        <div class="col-lg-4 bg-white px-2">
                            <img class="obj-fit" src="/images/{{$cart->options->imgpath}}" alt="">
                        </div>
                        <div class="col-lg-8 bg-white px-2">
                            <h2>{{ $cart->name }}</h2>
                            <p class="lead mb-1">{{$cart->qty}}</p>
                            <p class="lead text-danger mb-1">{{$cart->price . "円" ?? ""}}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <p class="text-center">カートはからっぽです。</p>
            @endif
            <a href="/">商品一覧へ</a>
        </div>
        {{-- サイドカラム --}}
        <div class="side col-lg-3 bg-white p-4">
            <p class="lead text-danger mb-2">{{$cart->price . "円" ?? ""}}</p>

        </div>
    </div>
</div>
@endsection