@extends('layouts.simple')
@section('title', 'Exapmle Shop')

@section('content')
<div class="container">
    <order
        :carts='@json($carts, JSON_FORCE_OBJECT)'
        :carts_info='@json($carts_info, JSON_FORCE_OBJECT)'
    />
</div>
@endsection