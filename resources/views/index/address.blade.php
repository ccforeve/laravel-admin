<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>我的收货地址</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/Swiper/3.4.2/css/swiper.min.css">
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/index.css">
</head>
<body>
<div id="mysite" class="flexv wrap">
    <div class="flexitemv mainbox">
        <!--头部-->
        <div class="flex center head">
            <a href="javascript:history.back(-1);" class="flex center xg xg-left"></a>
            <h1 class="flexitem center">我的收货地址</h1>
        </div>
        <!--没有收货地址-->

        <!--有收货地址-->
        @if(count($addresses))
            @foreach($addresses as $address)
                <div class="flex info">
                    <div class="flexitemv centerh information ck-address" data-url="{{ route('index.select_address', $address->id) }}">
                        <span class="flex name">{{ $address->name }}</span>
                        <span class="flex phone">{{ $address->phone }}</span>
                        <span class="flex address">{{ $address->province }}{{ $address->detail }}</span>
                    </div>
                    <a href="{{ route('address.edit', $address->id) }}" class="xg xg-edit"></a>
                </div>
            @endforeach
        @else
            <div class="flexitemv center sitebox">
                <div class="flex center site-img"><i class="xg xg-site"></i></div>
                <p class="flex center">您还没有收获地址，请新增地址</p>
                {{--<a href="{{ route('address.create') }}" class="flex center sitebtn">新增收货地址</a>--}}
            </div>
        @endif
    </div>
    <a href="{{ route('address.create') }}" class="flex center newsitebtn">新增收货地址</a>
</div>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script type="text/javascript">
    $('.ck-address').click(function () {
        var url = $(this).attr('data-url');
        $.get(url, function(ret){
            if(ret.state == 0) {
                window.location.href = ret.url;
            } else {
                showMsg('选择出错');
            }
        })
    });
</script>
</html>