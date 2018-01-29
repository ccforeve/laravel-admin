<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <style>
        div{
            padding: 15px 15px;
            border-bottom: 1px solid #e0d1ea;
        }
        .active{ color:orange }
    </style>
</head>
<body>
    @foreach($datas as $data)
        <div @if($loop->index == 1)class="active"@endif>{{ $data['context'] }}---{{ $data['time'] }}</div>
    @endforeach
</body>
</html>