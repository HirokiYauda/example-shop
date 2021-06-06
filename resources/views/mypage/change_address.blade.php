@extends('layouts.simple')
@section('title', 'Exapmle Shop')
@section('add_script')
<script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
@endsection

@section('content')
<div class="container">
    <h1 class="h3 text-center mb-4">住所変更</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white py-3 px-4">
        <form class="h-adr" method="POST" action="{{route('mypage_address_update')}}">
            @csrf
            @method('PUT')
            @include('templete.mypage_address_form');
            <div class="text-center"><button type="submit" class="btn btn-primary">変更</button></div>
        </form>
    </div>
</div>
@endsection