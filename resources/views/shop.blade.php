@extends('layouts.default')
@section('title', $page_name)
@section('description', "${page_name}のディスクリプション")
@section('canonical')
<link rel="canonical" href="{{url()->current()}}">
@endsection

@section('content')
<h1 class="main-title">「{{Str::limit(request()->free_word, 16, '...')}}」の<span class="br">検索結果　{{$products->count()}}件</span></h1>
@include('templete.sort_select')
@if($products->isNotEmpty())
<div class="row">
    @foreach($products as $product)
        <div class="col-sm-6 col-md-3 mb-4">
            <div class="card">
                <div class="img-hidden">
                    <a href="{{route('product_detail', ['product'=> $product->name_en])}}">
                        <img class="card-img-top" src="/images/{{$product->imgpath}}" alt="">
                    </a>
                </div>
                <dl class="card-body px-2 py-3">
                    <dt class="mb-1">
                        <a class="text-decoration-none h6" href="{{route('product_detail', ['product'=> $product->name_en])}}">
                            {{$product->name}}
                        </a>
                    </dt>
                    <dd>
                        <a class="btn btn-info mr-2 font06 text-white mb-2" href="{{route('category_narrowing_down', ['category' => $product->genre->category->name_en])}}">{{$product->genre->category->name}}</a>
                    </dd>
                    <dd class="lead text-danger mb-1">{{number_format($product->price_including_tax) . "円" ?? ""}}</dd>
                    <dd class="card-text"><small>{{$product->detail}}</small></dd>
                </dl>
                <div class="card-footer bg-white border-white text-center mb-2">
                    @if(Util::getAddQtyInCart($product->id) > 0)
                        <form action="{{ route('add_cart') }}" method="post">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $product->id }}">
                            <button type="submit" class="btn btn-outline-primary">カートに入れる</button>
                        </form>
                    @else
                        <p class="text-danger mb-1">
                            @if(empty($product->stock))
                                {{config('cart.no_stock_caution_message')}}
                            @else
                                {{config('cart.max_qty_caution_message')}}
                            @endif
                        </p>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
@else
    <p class="text-center">検索に該当する商品が存在しません。</p>
@endif

<div>
    {{  $products->appends(request()->input())->links()}}
</div>
@endsection