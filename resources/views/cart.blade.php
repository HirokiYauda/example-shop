@extends('layouts.default')
@section('title', 'Exapmle Shop')

@section('content')
<div class="container">
    <h1 class="h3 text-center mb-4">ショッピングカート</h1>
    <cart-list
        :carts='@json($carts, JSON_FORCE_OBJECT)'
        :carts_info='@json($carts_info, JSON_FORCE_OBJECT)'
        :caution_message="'{{session('caution_message') ?? ""}}'"
        :is_login= "{{(int)$is_login}}"
    />
</div>
@endsection