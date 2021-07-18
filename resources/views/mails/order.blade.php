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
    Example Shopをご利用いただき、ありがとうございます。<br>
    {{$mail_data['user_name']}}様からご注文を承ったことをお知らせいたします。<br>
    注文内容は、<a href="{{$url}}">管理画面</a>からご確認ください。<br>
    <br>
    注文番号: {{$mail_data['order_number']}}<br>
    <br>
    <a href="$url">{{$url}}</a>
</p>
</body>
</html>