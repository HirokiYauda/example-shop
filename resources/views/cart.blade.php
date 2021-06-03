@extends('layouts.default')
@section('title', 'Exapmle Shop')

@section('content')
<div class="container">
    <h1 class="h3 text-center mb-4">ショッピングカート</h1>
    <div class="row justify-content-between">
        <cart-list :carts='@json($carts, JSON_FORCE_OBJECT)' :caution_message="'{{session('caution_message') ?? ""}}'"></cart-list>
    </div>
</div>
@endsection