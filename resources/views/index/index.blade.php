@include('index._header')

<body id="index" class="flexv">
<div class="flexitemv scroll main">
    <div class="banner">
        <a href="{{ route('index.product_details', ['produst'=>1, 'pid'=>session('pid')]) }}"><img src="/index_images/banner.jpg" alt=""></a>
    </div>
    <div>
        <div style="float: left;width: 47%; margin: 0 2%; box-sizing: border-box; border: 2px solid green;">
            <a href="{{ route('index.product_details', ['produst'=>1, 'pid'=>session('pid')]) }}"><img src="/index_images/tz1.jpg" alt=""></a>
        </div>
        <div style="float: left;width: 47%;margin-right: 2%; box-sizing: border-box; border: 2px solid green;">
            <a href="{{ route('index.product_details', ['produst'=>1, 'pid'=>session('pid')]) }}"><img src="/index_images/tz2.jpg" alt=""></a>
        </div>
    </div>

    <div style="padding-top: 2vw">
        <div style="float: left;width: 47%;margin: 0 2%">
            <a href="javascript:;" class="in_info" data-url="{{ route('index.judge', 'free') }}"><img src="/index_images/user.jpg" alt=""></a>
        </div>
        <div style="float: left;width: 47%; margin-right: 2%;">
            <a href="javascript:;" class="in_info" data-url="{{ route('index.judge', 'experience') }}"><img src="/index_images/fhy.jpg" alt=""></a>
        </div>
    </div>

</div>

@include('index._footer')

@include('index._notice')

<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="/plugins/mobile/layer.js"></script>
<script src="/js/functions.js"></script>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    $('.in_info').click(function () {
        var url = $(this).attr('data-url');
        $.get(url, function (ret) {
            if(ret.state == 401){
                showMsg(ret.error, 0, 2000);
                if(ret.url) {
                    setTimeout(function () {
                        window.location.href = ret.url;
                    },2000);
                }
            }else{
                window.location.href = ret.url;
            }
        });
    });

    {{--wx.ready(function(){--}}
        {{--// config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。--}}
        {{--//分享给朋友--}}
        {{--wx.onMenuShareAppMessage({--}}
            {{--title: '美肤尚品商城，每一个女性的完美选择！', // 分享标题--}}
            {{--desc: '我淘到了一件好东西，分享给你一起看看吧！', // 分享描述--}}
            {{--link: 'http://www.meifusp.com/?pid={$Think.session.userid}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致--}}
            {{--imgUrl: 'http://www.meifusp.com/template/index/images/index-share.jpg', // 分享图标--}}
            {{--success: function () {--}}
                {{--// 用户确认分享后执行的回调函数--}}
            {{--}--}}
        {{--});--}}
        {{--//分享朋友圈--}}
        {{--wx.onMenuShareTimeline({--}}
            {{--title: '美肤尚品商城，每一个女性的完美选择！', // 分享标题--}}
            {{--link: 'http://www.meifusp.com/?pid={$Think.session.userid}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致--}}
            {{--imgUrl: 'http://www.meifusp.com/template/index/images/index-share.jpg', // 分享图标--}}
            {{--success: function () {--}}
                {{--// 用户确认分享后执行的回调函数--}}
            {{--}--}}
        {{--});--}}
    {{--});--}}
</script>

</body>
</html>