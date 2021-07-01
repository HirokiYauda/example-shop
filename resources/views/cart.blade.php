@extends('layouts.default')
@section('title', "カート一覧")
@section('description', "カート一覧のディスクリプション")

@section('content')
<div class="container">
    <h1 class="h3 text-center mb-4">ショッピングカート</h1>
    <cart-list
        :carts='@json($carts, JSON_FORCE_OBJECT)'
        :carts_info='@json($carts_info, JSON_FORCE_OBJECT)'
        :caution_messages='@json($caution_messages, JSON_FORCE_OBJECT)'
        :templete_messages='@json($templete_messages, JSON_FORCE_OBJECT)'
        :is_login= "{{(int)$is_login}}"
        :max_qty="{{$max_qty}}"
    />
</div>
@endsection