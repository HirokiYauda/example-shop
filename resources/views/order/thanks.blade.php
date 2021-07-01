@extends('layouts.simple')
@section('title', "購入完了")
@section('description', "購入完了のディスクリプション")


@section('content')
<div class="container-fluid">
   <div class="">
       <div class="mx-auto" style="max-width:1200px">
           <h1 class="text-center font-weight-bold" style="color:#555555;  font-size:1.2em; padding:24px 0px;">
           {{ Auth::user()->name }}さんご購入ありがとうございました</h1>

           <div class="card-body">
               <p>確認用のメールをお送りしております。お手続き完了次第商品を発送致します。</p>
               <a href="{{route('top')}}">トップへ戻る</a>
           </div>

           </div>
       </div>
   </div>
</div>
@endsection