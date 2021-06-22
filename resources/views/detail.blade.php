@extends('layouts.default')
@section('title', 'Exapmle Shop')

@section('content')
{{ Breadcrumbs::render('detail', $product) }}
<div class="container">
    <div class="row justify-content-between">
        {{-- メインカラム --}}
        <div class="column col-lg-8 bg-white p-4">
            <div class="row justify-content-between">
                <div class="col-lg-4 bg-white px-2">
                    <img class="obj-fit" src="/images/{{$product->imgpath}}" alt="">
                </div>
                <div class="col-lg-8 bg-white px-2">
                    <h2>{{ $product->name }}</h2>
                    <p class="lead text-danger mb-1">{{number_format($product->price_including_tax) . "円" ?? ""}}</p>
                    @if(empty($product->stock))
                        <p class="text-danger mb-1">{{config("cart.no_stock_caution_message")}}</p>
                    @endif
                    <p>{{ $product->detail }}</p>
                </div>
            </div>
        </div>
        {{-- サイドカラム --}}
        <div class="side col-lg-3 bg-white p-4">
            <p class="lead text-danger mb-2">{{number_format($product->price_including_tax) . "円" ?? ""}}</p>
            @if(!empty($product->stock) && $addQtyInCart > 0)
                <form action="{{ route('add_cart') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        数量：
                        <select name="qty" class="form-select form-select-sm" aria-label=".form-select-sm example">
                            @for ($count = 1; $count <= $addQtyInCart; $count++)
                                <option value="{{$count}}">{{$count}}</option>
                            @endfor
                        </select>
                    </div>
                    @if(!empty($product->stock) && $product->stock < config('cart.display_remaining_inventory_count') )
                        <p class="text-danger mb-1">残りの在庫数は、{{$product->stock}}点です。</p>
                    @endif
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button type="submit" class="btn btn-outline-primary">カートに入れる</button>
                </form>
            @else
                <p class="text-danger mb-1">
                    @if(empty($product->stock))
                        {{config('cart.no_stock_caution_message')}}
                    @elseif($addQtyInCart <= 0)
                        {{config('cart.max_qty_caution_message')}}
                    @endif
                </p>
                <button type="button" class="btn btn-secondary" disabled>カートに入れる</button>
            @endif
        </div>
    </div>
</div>
@endsection