@extends('layouts.default')
@section('title', 'Exapmle Shop')

@section('content')
{{ Breadcrumbs::render('detail', $stock) }}
<div class="container">
    <div class="row justify-content-between">
        {{-- メインカラム --}}
        <div class="column col-lg-8 bg-white p-4">
            <div class="row justify-content-between">
                <div class="col-lg-4 bg-white px-2">
                    <img class="obj-fit" src="/images/{{$stock->imgpath}}" alt="">
                </div>
                <div class="col-lg-8 bg-white px-2">
                    <h2>{{ $stock->name }}</h2>
                    <p class="lead text-danger mb-1">{{number_format($stock->price) . "円" ?? ""}}</p>
                    <p>{{ $stock->detail }}</p>
                </div>
            </div>
        </div>
        {{-- サイドカラム --}}
        <div class="side col-lg-3 bg-white p-4">
            <p class="lead text-danger mb-2">{{number_format($stock->price) . "円" ?? ""}}</p>
            <form action="{{ route('add_cart') }}" method="post">
                @csrf
                <div class="mb-3">
                    数量：
                    <select name="qty" class="form-select form-select-sm" aria-label=".form-select-sm example">
                        @for ($count = 1; $count <= 10; $count++)
                            <option value="1">{{$count}}</option>
                        @endfor
                    </select>
                </div>
                <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                <button type="submit" class="btn btn-outline-primary">カートに入れる</button>
            </form>
        </div>
    </div>
</div>
@endsection