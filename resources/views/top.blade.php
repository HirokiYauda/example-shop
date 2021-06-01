@extends('layouts.default')
@section('title', 'Exapmle Shop')

@section('content')
<h1 class="h3 text-center mb-3">商品一覧</h1>
<div class="row">
    @foreach($stocks as $stock)
        <div class="col-sm-6 col-md-3 mb-4">
            <div class="card h-100">
                <a href="{{route('product_detail', ['product'=> $stock->name_en, 'category_id' => $stock->genre->category->id, 'genre_id' => $stock->genre->id])}}">
                    <img class="bd-placeholder-img card-img-top obj-fit" src="/images/{{$stock->imgpath}}" alt="">
                </a>
                <div class="card-body px-2 py-3">
                    <h5>
                        <a class="text-decoration-none h6" href="{{route('product_detail', ['product'=> $stock->name_en, 'category_id' => $stock->genre->category->id, 'genre_id' => $stock->genre->id])}}">
                            {{$stock->name}}
                        </a>
                    </h5>
                    <a class="btn btn-info mr-2 font06 text-white mb-2" href="{{route('category_narrowing_down', ['category' => $stock->genre->category->name_en])}}">{{$stock->genre->category->name}}</a>
                    <p class="lead text-danger mb-1">{{number_format($stock->price) . "円" ?? ""}}</p>
                    <p class="card-text"><small>{{$stock->detail}}</small></p>
                </div>
                <div class="card-footer bg-white border-white text-center mb-2">
                    <form action="mycart" method="post">
                        @csrf
                        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                        <button type="submit" class="btn btn-outline-primary">カートに入れる</button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="text-center" style="width: 200px;margin: 20px auto;">
    {{  $stocks->links()}}
</div>
@endsection