@extends('layouts.simple')
@section('title', 'Exapmle Shop')

@section('content')
<div class="container">
    <h1 class="h3 text-center mb-4">注文確認</h1>
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
                            <dd>{{$user->full_address ? $user->full_address : "住所が登録されていません"}}</dd>
                        </dl>
                        <div><a type="button" href="{{route('change_address')}}" class="btn btn-light">変更</a></div>
                    </div>
                </div>

                <!-- 注文する商品 -->
                @foreach($carts as $cart)
                    <div class="row justify-content-between bg-white px-2 py-3 mb-3">
                        <div class="col-lg-4 bg-white px-2">
                            <img
                                class="obj-fit"
                                src="/images/{{$cart->options->imgpath}}"
                                alt=""
                            />
                        </div>
                        <div class="col-lg-8 bg-white px-2">
                            <h2>{{$cart->name}}</h2>
                            <p>数量: {{$cart->qty}}</p>
                            <p class="lead text-danger mb-1">
                                {{$cart->price ? $cart->price . "円" : ""}}
                            </p>
                            @if($cart->qty > $product::find($cart->id)->stock)
                                <p class="text-danger mb-1">
                                    この商品は、一時的に在庫切れ、もしくは購入数量が在庫数よりも多いため、入荷時期が未定の商品です。
                                </p>
                            @endif
                        </div>
                    </div>
                @endforeach
                
            </div>
        </div>
        <!-- サイドカラム -->
        <div class="side col-lg-3 bg-white p-4">
            <p>お支払い金額({{$carts_info['count']}}点)</p>
            <p>{{$carts_info['total']}}円 (税込)</p>
            @if(empty($caution_messages))
                <button type="submit" class="btn btn-outline-primary">注文を確定する</button>
            @else
                <button type="button" class="btn btn-secondary" disabled>注文を確定する</button>
            @endif
        </div>
    </div>
</div>
@endsection