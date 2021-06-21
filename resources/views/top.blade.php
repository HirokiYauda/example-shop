@extends('layouts.default')
@section('title', 'Exapmle Shop')

@section('content')
<h1 class="h3 text-center mb-4">商品一覧</h1>
@include('templete.sort_select')
<div class="row">
    @foreach($products as $product)
        <div class="col-sm-6 col-md-3 mb-4">
            <div class="card h-100">
                <a href="{{route('product_detail', ['product'=> $product->name_en, 'category_id' => $product->genre->category->id, 'genre_id' => $product->genre->id])}}">
                    <img class="bd-placeholder-img card-img-top obj-fit" src="/images/{{$product->imgpath}}" alt="">
                </a>
                <div class="card-body px-2 py-3">
                    <h5>
                        <a class="text-decoration-none h6" href="{{route('product_detail', ['product'=> $product->name_en, 'category_id' => $product->genre->category->id, 'genre_id' => $product->genre->id])}}">
                            {{$product->name}}
                        </a>
                    </h5>
                    <a class="btn btn-info mr-2 font06 text-white mb-2" href="{{route('category_narrowing_down', ['category' => $product->genre->category->name_en])}}">{{$product->genre->category->name}}</a>
                    <p class="lead text-danger mb-1">{{number_format($product->price) . "円" ?? ""}}</p>
                    <p class="card-text"><small>{{$product->detail}}</small></p>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="text-center" style="width: 200px;margin: 20px auto;">
    {{  $products->appends(request()->input())->links()}}
</div>
@endsection