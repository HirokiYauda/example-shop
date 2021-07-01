@extends('layouts.simple')
@section('title', "購入完了")
@section('description', "購入完了のディスクリプション")


@section('content')
<div class="container-fluid">
   <div>
       <div class="mx-auto">
           <h1 class="thanks-title">
           <span class="br">{{ Auth::user()->name }}さん</span>ご購入ありがとうございました</h1>

           <div class="text-center">
               <p>確認用のメールをお送りしております。<br>お手続き完了次第商品を発送致します。</p>
               <a href="{{route('top')}}">トップへ戻る</a>
           </div>

           </div>
       </div>
   </div>
</div>
@endsection