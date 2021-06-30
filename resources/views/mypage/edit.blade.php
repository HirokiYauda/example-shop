@extends('layouts.simple')
@section('title', 'Exapmle Shop')
@section('add_script')
<script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
@endsection

@section('content')
<div class="container">
    <h1 class="h3 text-center mb-4">アカウント管理</h1>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('update_message'))
        <p class="text-danger mb-3 text-center lead">
            {{ session('update_message') }}
        </p>
    @endif

    <div class="bg-white py-3 px-4">
        <form class="h-adr" method="POST" action="{{route('mypage_full_update')}}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">名前</label>
                <input type="text" value="{{old('name', $user->name ?? "")}}" name="name" class="form-control" id="name">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">メールアドレス</label>
                <input type="email" value="{{old('email', $user->email ?? "")}}" name="email" class="form-control" id="email">
            </div>
            @include('templete.mypage_address_form')
            <div class="text-center"><button type="submit" class="btn btn-primary">変更</button></div>
        </form>
    </div>
</div>
@endsection