@include('index._header')

<body id="poster" class="flex center">
<div class="main">
    <div class="content">
        <p class="title">我是<span style="color: #ea68a2">{{ $user->nickname }}</span><br>向你推荐一个超赞的美妆平台<br>快来跟我一起变美吧！</p>
        <img class="logo" src="/index_images/logo.png" alt="">
        <p class="text" style="color:#7a7a7a;">永久免费使用？疯了吧?<br>不，这是真的 ——<br><span style="font-size: 1.2em;">关注美肤尚品商城，</span>一次付费，<br><strong style="color:#ea68a2;font-size: 1.5em;">永久免费</strong>领取试用装！</p>
    </div>
    <div class="flexv centerv tips">
        <div class="img"><img src="{{ $qrcode_url }}" alt=""></div>
        <p>长按此图，识别我的二维码！</p>
        <p>获取不老的秘籍！</p>
    </div>
</div>
<div class="flexv cover">
    <i class="iconfont icon-share"></i>
    <i class="iconfont icon-arrow"></i>
    <p>请点击右上角<br>将它发送给指定朋友<br>或分享到朋友圈</p>
</div>
<script src="http://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script>
    setTimeout(function () {
        $('.cover').fadeOut();
    }, 2000);
</script>
</body>
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>

    // wx.config({
    //     debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    //     appId: '{$signPackage["appId"]}', // 必填，公众号的唯一标识
    //     timestamp: {$signPackage["timestamp"]}, // 必填，生成签名的时间戳
    //     nonceStr: '{$signPackage["nonceStr"]}', // 必填，生成签名的随机串
    //     signature: '{$signPackage["signature"]}',// 必填，签名，见附录1
    //     jsApiList: [
    //         'onMenuShareTimeline', //分享到朋友圈
    //         'onMenuShareAppMessage', //分享给好友
    //         'addCard'
    //     ] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    // });
    // wx.ready(function (){
    //     wx.onMenuShareAppMessage({
    //         title: '推广海报', // 分享标题
    //         desc: '我是{$nickname},向你推荐一个超赞的美妆平台,快来跟我一起变美吧！', // 分享描述
    //         link: 'http://www.meifusp.com/haibao.html?uid={$Think.get.uid}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
    //         imgUrl: 'http://www.meifusp.com/template/index/images/logo.png', // 分享图标
    //         success: function () {
    //             // 用户确认分享后执行的回调函数
    //         },
    //         cancel: function () {
    //             // 用户取消分享后执行的回调函数
    //         }
    //     });
    //     wx.onMenuShareTimeline({
    //         title: '推广海报', // 分享标题
    //         link: 'http://www.meifusp.com/haibao.html?uid={$Think.get.uid}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
    //         imgUrl: 'http://www.meifusp.com/template/index/images/logo.png', // 分享图标
    //         success: function () {
    //             // 用户确认分享后执行的回调函数
    //         },
    //         cancel: function () {
    //             // 用户取消分享后执行的回调函数
    //         }
    //     });
    // });
    //
    // wx.error(function (res) {
    //     alert(res.errMsg);  //打印错误消息。及把 debug:false,设置为debug:ture就可以直接在网页上看到弹出的错误提示
    // });
</script>
</html>