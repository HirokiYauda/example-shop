@extends('layouts.default')
@section('description', 'TOPのディスクリプション')
@section('canonical')
<link rel="canonical" href="{{env('APP_URL')}}">
@endsection

@section('content')
<h1 class="main-title">商品一覧</h1>
@include('templete.sort_select')
<div class="row">
    @foreach($products as $product)
        <div class="col-sm-6 col-md-3 mb-4">
            <div class="card">
                <div class="img-hidden">
                    <a href="{{route('product_detail', ['product'=> $product->name_en])}}">
                        <img class="card-img-top" src="/images/{{$product->imgpath}}" alt="{{$product->name_en}}">
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
            </div>
        </div>
    @endforeach
</div>

<div>
    {{  $products->appends(request()->input())->links()}}
</div>
@endsection