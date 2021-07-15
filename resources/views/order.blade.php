@extends('layouts.simple')
@section('title', "注文確認")
@section('description', "注文確認のディスクリプション")

@section('content')
<div class="container">
    <h1 class="main-title">注文確認</h1>
    <div class="row justify-content-between">
        <div class="column col-lg-8">
            @if($caution_messages)
                <ul>
                    @foreach ($caution_messages as $caution_message)
                        <li class="lead text-danger mb-1">{{$caution_message}}</li>
                    @endforeach
                </ul>
            @endif

            <!-- メインカラム -->
            <div key="existItem">
                <div class="row justify-content-between bg-white px-2 py-3 mb-3">
                    <div class="bg-white px-2">
                        <dl>
                            <dt>お届け先住所</dt>
                            @if (session('update_message'))
                                <p class="text-danger">{{ session('update_message') }}</p>
                            @endif
                            <dd>{{$user->full_address ? $user->full_address : "住所が登録されていません"}}</dd>
                        </dl>
                        <div><a type="button" href="{{route('change_address')}}" class="btn btn-light">変更</a></div>
                    </div>
                </div>

                <!-- 注文する商品 -->
                @foreach($carts as $cart)
                    @php
                        $stock = $product::find($cart->id)->stock;
                    @endphp
                    <div class="row justify-content-between bg-white px-2 py-3 mb-3">
                        <div class="col-lg-4 bg-white px-2 column__item">
                            <img
                                class="obj-fit"
                                src="{{Storage::disk('s3')->url("uploads/{$cart->options->imgpath}")}}"
                                alt=""
                            />
                        </div>
                        <div class="col-lg-8 bg-white px-2 column__item">
                            <h2>{{$cart->name}}</h2>
                            <p>数量: {{$cart->qty}}</p>
                            <p class="lead text-danger mb-1">
                                {{$cart->options->price_including_tax ? $cart->options->price_including_tax . "円" : ""}}
                            </p>
                            @if(empty($stock))
                                <p class="text-danger mb-1">{{config("cart.no_stock_caution_message")}}</p>
                            @elseif($cart->qty > $stock)
                                <p class="text-danger mb-1">
                                    残りの在庫数は、{{$stock}}点です。<br>
                                    {{config('cart.max_qty_caution_message')}}
                                </p>
                            @elseif($stock < 10)
                                <p class="text-danger mb-1">残りの在庫数は、{{$stock}}点です。</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        <!-- サイドカラム -->
        <div class="side col-lg-3 bg-white p-4">
            <form action="{{route('purchase')}}" method="POST">
                @csrf
                <p>お支払い金額({{$carts_info['count']}}点)</p>
                <p>{{$carts_info['total']}}円 (税込)</p>
                @if(empty($caution_messages))
                    <button type="submit" class="btn btn-outline-primary">注文を確定する</button>
                @else
                    <button type="button" class="btn btn-secondary" disabled>注文を確定する</button>
                @endif
            </form>
        </div>
    </div>
</div>
@endsection