@extends('layouts.default')
@section('title', "注文履歴")
@section('description', "注文履歴のディスクリプション")

@section('content')
<h1 class="main-title">注文履歴</h1>
@if(!$orderYears->isEmpty())
    <div class="row">
        <div class="col-2 clearfix mb-3 pr-0">
                <select id="sort_history" name="sort_history" class="form-control">
                    @foreach($orderYears as $orderYear)
                        <option value="{{$orderYear}}" {{$orderYear === request('sort_history', "") ? 'selected' : ""}}>{{$orderYear}}</option>
                    @endforeach
                </select>
        </div>
        <div class="col-10 pt-1">に注文された<strong class="lead">{{$orders->count()}}</strong>件を表示</div>
    </div>
@endif

<div>
    @if(!$orders->isEmpty())
        @foreach($orders as $order)
            <div class="bg-white px-2 py-3 mb-4 border">
                <div class="bg-light">
                    <div class="row px-3 pt-3 mb-2">
                        <dl class="col-2">
                            <dt>注文日</dt>
                            <dd>{{\Carbon\Carbon::parse($order->created_at)->format('Y年m月d日')}}</dd>
                        </dl>
                        <dl class="col-2">
                            <dt>合計金額</dt>
                            <dd>{{number_format($order->total) . "円" ?? ""}}</dd>
                        </dl>
                        <dl class="col-5">
                            <dt>お届け先</dt>
                            <dd>{{$order->full_address}}</dd>
                        </dl>
                        <dl class="col-3">
                            <dt>注文番号</dt>
                            <dd>{{$order->order_number}}</dd>
                        </dl>
                    </div>
                </div>
                
                @foreach ($order->orderDetail as $orderDetail)
                    <div class="row justify-content-between bg-white mx-2 py-3 my-3 mb-3 border-bottom">
                        <div class="col-lg-2 bg-white px-2">
                            <a href="{{route('product_detail', ['product' => $orderDetail->product->name_en])}}">
                                <img
                                    class="obj-fit"
                                    src="/images/{{$orderDetail->product->imgpath}}"
                                    alt=""
                                />
                            </a>
                        </div>
                        <div class="col-lg-10 bg-white px-2">
                            <p class="mb-1">{{ $orderDetail->product->name }}</p>
                            <p class="lead text-danger mb-2">
                                {{number_format($orderDetail->price_including_tax) . "円" ?? ""}}
                            </p>
                            <a class="btn btn-info mr-2 font06 text-white mb-2" href="{{route('product_detail', ['product' => $orderDetail->product->name_en])}}">
                                再度購入する
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    @else
        <p class="text-center">注文履歴がありません</p>
    @endif
</div>
@endsection