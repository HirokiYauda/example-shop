@extends('layouts.default')
@section('title', "注文履歴")
@section('description', "注文履歴のディスクリプション")

@section('content')
<h1 class="main-title">注文履歴</h1>
<div class="history">
    @if(!$orderYears->isEmpty())
        <div class="history__sort">
            <div class="history__sort--select">
                <select id="sort_history" name="sort_history" class="form-control">
                    @foreach($orderYears as $orderYear)
                        <option value="{{$orderYear}}" {{$orderYear === request('sort_history', "") ? 'selected' : ""}}>{{$orderYear}}</option>
                    @endforeach
                </select>
            </div>
            <p class="history__sort--text">に注文された<strong class="lead">{{$orders->count()}}</strong>件を表示</p>
        </div>
    @endif

    @if(!$orders->isEmpty())
        @foreach($orders as $order)
            <div class="history__list">
                <div class="history__list__header">
                    <dl class="history__list__header__list">
                        <dt>注文日</dt>
                        <dd>{{\Carbon\Carbon::parse($order->created_at)->format('Y年m月d日')}}</dd>
                    </dl>
                    <dl class="history__list__header__list">
                        <dt>合計金額</dt>
                        <dd>{{number_format($order->total) . "円" ?? ""}}</dd>
                    </dl>
                    <dl class="history__list__header__list">
                        <dt>お届け先</dt>
                        <dd>{{$order->full_address}}</dd>
                    </dl>
                    <dl class="history__list__header__list">
                        <dt>注文番号</dt>
                        <dd>{{$order->order_number}}</dd>
                    </dl>
                </div>
                
                @foreach ($order->orderDetail as $orderDetail)
                    <div class="history__list__block column">
                        <div class="col-lg-2 bg-white px-2 column__item">
                            <a href="{{route('product_detail', ['product' => $orderDetail->product->name_en, 'id' => $orderDetail->product_id])}}">
                                <img
                                    class="obj-fit"
                                    src="/images/{{$orderDetail->product->imgpath}}"
                                    alt=""
                                />
                            </a>
                        </div>
                        <div class="col-lg-10 bg-white px-2 column__item">
                            <p class="h4 mb-1">{{ $orderDetail->product->name }}</p>
                            <p>購入数量: {{ $orderDetail->qty }}</p>
                            <p class="lead text-danger mb-2">
                                {{number_format($orderDetail->price_including_tax) . "円" ?? ""}}
                            </p>
                            <a class="btn btn-info mr-2 font06 text-white mb-2" href="{{route('product_detail', ['product' => $orderDetail->product->name_en, 'id' => $orderDetail->product_id])}}">
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