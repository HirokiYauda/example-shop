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
        <form class="h-adr" method="POST" action="{{route('mypage_update')}}">
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
            <div class="mb-3">
                <span class="p-country-name" style="display:none;">Japan</span>
                <label for="zip" class="form-label">郵便番号</label>
                <input type="text" name="zip" value="{{old('zip', $user->zip ?? "")}}" class="p-postal-code form-control" id="zip" size="8" maxlength="8">
            </div>
            <div class="mb-3">
                <label for="pref" class="form-label">都道府県</label>
                <select name="pref" class="form-control p-region-id" id="pref">
                    @foreach ($prefs as $pref)
                        <option value="{{$pref->id}}" @if(old('pref', $user->pref ?? 0) == $pref->id) selected @endif>{{$pref->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="address1" class="form-label">住所1</label>
                <input type="text" value="{{old('address1', $user->address1 ?? "")}}" name="address1"
                    class="form-control p-locality p-street-address p-extended-address" id="address1" placeholder="市、区、町、丁目、番地、号" />
            </div>
            <div class="mb-3">
                <label for="address2" class="form-label">住所2</label>
                <input type="text" value="{{old('address2', $user->address2 ?? "")}}" name="address2" class="form-control" id="address2" placeholder="マンション、アパート、部屋番号、等" />
            </div>
            <div class="text-center"><button type="submit" class="btn btn-primary">変更</button></div>
        </form>
    </div>
</div>
@endsection