<title>商品详情</title>

@include('index._header')

<style>
    .details img{
        width: 100% !important;
        height: auto !important;
    }
    .telNum{  border: 1px solid #e6e6e6;  height: 35px;  width: 80%;  text-align: center;  }
    .sBtn{  width: 40px;  background: #60B7DA;  padding: 5px 10px;  margin-top: 8px;  color: #fff;}
</style>
<link href="http://cdn.bootcss.com/Swiper/3.4.2/css/swiper.min.css" rel="stylesheet">

<body id="goods" class="flexv">

    @if($product->audio)
        <div id="audio-btn" class="audio_on">
            <div class="rotate"></div>
            <audio src="{{ $product->audio }}" autoplay loop preload id="music"></audio>
        </div>
    @endif

    <div id="msg">
        <div class="swiper-wrapper">
            @foreach($orders as $order)
                <div class="flex centerv msg-item swiper-slide">
                    @if($order->user['head'])
                        <img src="{{ $order->user->head }}">
                    @else
                        <img src="/index_images/head.jpg">
                    @endif
                    <p class="f12">恭喜{{ $order->user['nickname'] }}购买{{ $order->product['name'] }}！{{ \Carbon\Carbon::parse($order->pay_time)->diffForHumans() }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <form class="flexitemv scroll main" id="form" action="{{ route('index.order') }}">
        {{ csrf_field() }}

        <div class="img">
            <img src="{{ config('app.index_image').$product->photo[1]}}">
        </div>

        <div class="flex limit">
            <div class="flex centerv limit__item limit__left">
                <p class="limit__t">限时购</p>
                <p class="limit__m">3天后发货</p>
            </div>
            <div class="flex centerv count-down limit__item" data-end="{{ strtotime($product->tl_end_time) }}">
                <p class="countdown__text">距结束</p>
                <p class="countdown__time"><span class="day"></span>天<span class="hour">00</span>:<span class="min">00</span>:<span class="sec">00</span>
                </p>
                <p class="countdown__i iconfont icon-go"></p>
            </div>
        </div>

        <div class="flexv centerh profile">
            <h2>{{ $product->name }}</h2>

            <div class="flexv bottom">
                @if($product->type ==2)
                    <p class="new-price">¥
                        @if($activity_check == 1)
                            0.01
                        @elseif($activity_check == 2)
                            {{ number_format($product->tl_one_off, 2) }}
                        @elseif($activity_check == 3)
                            {{ number_format($product->tl_two_off, 2) }}
                        @else
                            {{ number_format($product->price, 2) }}
                        @endif
                    </p>
                @else
                    <p class="new-price">¥ 0.00</p>
                @endif

                <div class="flex text">
                    <p class="old-price">价格：¥<del>{{ $product->original_price, 2 }}</del></p>
                    <p>好评度：<span>{{ $product->praise }}%</span></p>
                    <p>销售量：{{ $product->buy_count }}件</p>
                </div>

                <div class="flex centerv tips">
                    <p><i class="iconfont icon-gou"></i>企业认证</p>
                    <p><i class="iconfont icon-gou"></i>担保交易</p>
                    <p><i class="iconfont icon-gou"></i>正品保障</p>
                    <p><i class="iconfont icon-gou"></i>假一罚十</p>
                </div>
            </div>
        </div>

        @if($product->type == 1)
            <div class="spec">
                <div class="flex centerv item spec-btn">
                    <p>活动价</p>
                    <div class="sp">
                        @if($activity_check == 1)
                            <label class="select"><input type="radio" class="spec" hidden checked>¥ 0.01</label>
                        @elseif($activity_check == 2)
                            <label class="select"><input type="radio" class="spec" hidden checked>¥ 10.00</label>
                        @elseif($activity_check == 3)
                            <label class="select"><input type="radio" class="spec" hidden checked>¥ 15.00</label>
                        @else
                            <label class="select"><input type="radio" class="spec" hidden checked>¥ 15.00</label>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <input type="hidden" name="product_id" value="{{ $product->id }}" />
        <input type="hidden" name="product_type" value="{{ $product->type }}" />

        @if($activity_check == 1)
            <input type="hidden" name="original_price" value="0.01" />
            <input type="hidden" name="activity" value="1"><!-- 免费活动标记 -->
        @elseif($activity_check == 2)
            @if($product->type ==2)
                <input type="hidden" name="original_price" value="{{ $product->tl_one_off }}" />
            @else
                <input type="hidden" name="original_price" value="10" />
            @endif
            <input type="hidden" name="activity" value="2"><!-- 折扣活动标记 -->
        @elseif($activity_check == 3)
            @if($product->type ==2)
                <input type="hidden" name="original_price" value="{{ $product->tl_two_off }}" />
            @else
                <input type="hidden" name="original_price" value="15" />
            @endif
            <input type="hidden" name="activity" value="3"><!-- 折扣活动标记 -->
        @elseif($activity_check == 4)
            @if($product->type ==2)
                <input type="hidden" name="original_price" value="{{ $product->price }}" />
            @else
                <input type="hidden" name="original_price" value="15" />
            @endif
            <input type="hidden" name="activity" value="4"><!-- 活动恢复原价标记 -->
        @endif

        <div class="flexv centerv title">
            <p class="line"></p>
            <h2>商品详情</h2>
        </div>
        <div class="details">
            {!! $product->details !!}
            <p style="margin-top: 10px;color: #777">注：因厂家会在没有任何提前通知的情况下更改产品包装、产地或者一些附件，本司不能确保客户收到货物与商城图片、产地附件说明一致。</p>
        </div>
    </form>
<div class="flex footer">
    <div class="flexv footer-minitem">
        <a href="/index" class="flexitemv center">
            <i class="iconfont icon-index"></i>
            <p>主页</p>
        </a>
    </div>
    <div class="flexv footer-minitem">
        <a href="/user" class="flexitemv center">
            <i class="iconfont icon-center"></i>
            <p>我的</p>
        </a>
    </div>
    <div class="flexv footer-minitem" id="kefu">
        <a href="javascript:" id="good" class="flexitemv center">
            <i class="iconfont icon-cservice"></i>
            <p>客服</p>
        </a>
    </div>
    <div class="flexitemv center footer-item">
        <p class="price">
            总计：¥
            @if($activity_check == 1)
                0.01
            @elseif($activity_check == 2)
                @if($product->type == 1)
                    10.00
                @else
                    {{ number_format($product->tl_one_off, 2) }}
                @endif
            @elseif($activity_check == 3)
                @if($product->type == 1)
                    15.00
                @else
                    {{ number_format($product->tl_two_off, 2) }}
                @endif
            @else
                @if($product->type == 1)
                    15.00
                @else
                    {{ number_format($product->price, 2) }}
                @endif
            @endif
        </p>

        @if($product->type == 2)
            <p class="postage" price="0.00">包邮</p>
        @endif
    </div>
    <div class="flexitemv sub">
        <a href="javascript:;" class="flexitem center sub">提交订单</a>
    </div>
</div>

<div id="soldOut">
    <div class="flexv centerv soldOut__content">
        <img src="/index_images/soldout.png">
        <p>商品已售罄，请等待下次开售！</p>
        <a href="/index" class="flex center">返回商城首页</a>
    </div>
</div>

<!-- 等待付款弹窗 -->
<div class="layer-count" style="display: none">
    <div class="layer-count__content">
        <img src="/index_images/1.png" class="layer-count__bg">
        <img src="/index_images/2.png" class="layer-count__pointer">
    </div>
</div>

    <script src="https://cdn.bootcss.com/jquery/2.1.0/jquery.min.js"></script>
    <script src="/plugins/mobile/layer.js"></script>
    <script src="/js/checkform.js"></script>
    <script src="/js/functions.js"></script>
    <script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
    <script src="http://cdn.bootcss.com/Swiper/3.4.2/js/swiper.jquery.min.js"></script>
    <script src="https://cdn.bootcss.com/moment.js/2.19.0/moment.min.js"></script>
    <script src="/js/time-out.js"></script>
    <script>
        <!-- 售罄弹窗 -->
        @if($product->shelves == 0 || $product->stock == 0)
            var $soldout = $('#soldOut');
                $soldout.show();
            $soldout.on('click', function () {
                    window.location.href = '/index';
            });
        @endif

        $('.item label').click(function () {
            $(this).addClass('select').siblings().removeClass('select');
        });

        //    轮播
        new Swiper ('#msg', {
            loop: true,
            autoplay: 3000,
            autoplayDisableOnInteraction : false,
            direction : 'vertical'
        });

    new checkForm({
        form : '#form',
        btn : '.sub',
        error : function (e,msg){
            $('.layer-count').css('display', 'block');
        },
        complete : _.throttle(function (form){
            showProgress('提交订单中');
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            $.post(url, datas, function (ret) {
                console.log(ret);
                hideProgress();
                if(ret.state == 0) {
                    showMsg(ret.error, 1, 1500);
                    setTimeout(function () {
                        window.location.href = ret.url;
                    }, 1500);
                } else {
                    showMsg(ret.error, 0, 1500);
                    setTimeout(function () {
                        window.location.reload();
                    }, 1500);
                }
            }, 'json');
        }, 2000, { 'trailing': false })
    });

    var good = document.querySelector('#good');
    if (good) {
        good.addEventListener('click', function () {
            layer.open({
                content: '<div class="flexv center ins"><img src="/kefu.jpg" alt=""><p>长按识别二维码咨询</p></div>'
                , btn: ['取消']
                ,anim: 'up'
                , skin: 'footer'
                , yes: function (index) {
                    layer.close(index);
                }
            });
        });
    }
</script>

<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    // wx.config({
    //     debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
    //     appId: '{$package.appId}', // 必填，公众号的唯一标识
    //     timestamp: {$package.timestamp}, // 必填，生成签名的时间戳
    //     nonceStr: '{$package.nonceStr}', // 必填，生成签名的随机串
    //     signature: '{$package.signature}',// 必填，签名，见附录1
    //     jsApiList: ['onMenuShareTimeline','onMenuShareAppMessage'] // 必填，需要使用的JS接口列表，所有JS接口列表见附录2
    // });
    // wx.ready(function(){
    //     // config信息验证后会执行ready方法，所有接口调用都必须在config接口获得结果之后，config是一个客户端的异步操作，所以如果需要在页面加载时就调用相关接口，则须把相关接口放在ready函数中调用来确保正确执行。对于用户触发时才调用的接口，则可以直接调用，不需要放在ready函数中。
    //     //分享给朋友
    //     document.addEventListener("WeixinJSBridgeReady", function () {
    //         document.getElementById('music').play();
    //     }, false);
    //
    //     WeixinJSBridge.invoke('getNetworkType', {}, function(e) {
    //         document.getElementById('music').play();
    //     });
    //     wx.onMenuShareAppMessage({
    //         title: '{$res.p_name}', // 分享标题
    //         desc: '我淘到了一件好东西，分享给你一起看看吧！', // 分享描述
    //         link: 'http://www.meifusp.com/index/index/prddetail/id/{$res.id}/pid/{$Think.session.userid}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
    //         imgUrl: 'http://www.meifusp.com{:config('image_path').$res.p_ddphoto}', // 分享图标
    //         success: function () {
    //             // 用户确认分享后执行的回调函数
    //         }
    //     });
    //     //分享朋友圈
    //     wx.onMenuShareTimeline({
    //         title: '{$res.p_name}', // 分享标题
    //         link: 'http://www.meifusp.com/index/index/prddetail/id/{$res.id}/pid/{$Think.session.userid}', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
    //         imgUrl: 'http://www.meifusp.com{:config('image_path').$res.p_ddphoto}', // 分享图标
    //         success: function () {
    //             // 用户确认分享后执行的回调函数
    //         }
    //     });
    // });

        @if($product->audio)
            var $audioBtn = $('#audio-btn');
            $audioBtn.on('click', function () {
                var audio = document.getElementById('music');

                if (audio.paused) {
                    audio.play();
                    $audioBtn.removeClass('off').addClass('audio_on').find('div').addClass('rotate');
                } else {
                    audio.pause();
                    $audioBtn.removeClass('audio_on').addClass('off').find('div').removeClass('rotate');
                }
            });
        @endif
    </script>
</body>
</html>