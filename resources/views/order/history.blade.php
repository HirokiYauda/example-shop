@extends('layouts.default')
@section('title') Exapmle Shop @endsection

@section('content')
<h1 class="h3 text-center mb-4">注文履歴</h1>
<div class="row">
    {{-- @foreach($products as $product)
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
    @endforeach --}}
</div>

<div class="text-center" style="width: 200px;margin: 20px auto;">
    {{  $products->appends(request()->input())->links()}}
</div>
@endsection