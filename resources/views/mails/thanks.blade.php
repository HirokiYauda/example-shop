@php
    $url = config('app.url');
@endphp
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Example Shop</title>
</head>
<body>
<p>
    {{$mail_data['user_name']}}様<br>
    Example Shopをご利用いただき、ありがとうございます。<br>
    お客様のご注文を承ったことをお知らせいたします。<br>
    <br>
    注文内容は、<a href="{{$url . "/order/history?sort_history=" . date('Y')}}">注文履歴</a>からご確認ください。<br>
    商品が発送されましたら、Eメールでお知らせいたします。<br>
    <br>
    注文番号: {{$mail_data['order_number']}}<br>
    お届け先: {{$mail_data['full_address']}}<br>
    注文合計: {{$mail_data['total']}}円<br>
    <br>
    またのご利用をお待ちしております。<br>
    <a href="{{$url}}">{{$url}}</a>
</p>
</body>
</html>