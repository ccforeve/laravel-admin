@include('index._header')

<body id="center" class="flexv">
<div class="flexv center header">
    <div class="img"><img src="{{ $user->head }}" alt=""></div>
    <p class="tel">{{ $user->nickname }}</p>
</div>
<div class="flexitemv centerv scroll main">
    <h2>管理菜单</h2>
    <p class="line"></p>
    <ul class="list">
        <li class="flexitemv">
            <a href="{{ route('index.order_list', 2) }}" class="flexitemv center">
                <p class="flex center icon"><i class="iconfont icon-order" style="color: #f37171;"></i></p>
                <p>我的订单</p>
            </a>
        </li>
        <li class="flexitemv">
            <a href="{{ route('index.reward') }}" class="flexitemv center">
                <p class="flex center icon"><i class="iconfont icon-reward" style="color: #f3d527;"></i></p>
                <p>推广奖励</p>
            </a>
        </li>
        {{--<li class="flexitemv">--}}
            {{--<a href="{:url('WechatServer/bindWechat')}" class="flexitemv center">--}}
                {{--<p class="flex center icon"><i class="iconfont icon-wechat" style="color: #3cc673;"></i></p>--}}
                {{--<p>微信绑定</p>--}}
            {{--</a>--}}
        {{--</li>--}}
        <li class="flexitemv">
            <a href="exchange" class="flexitemv center">
                <p class="flex center icon"><i class="iconfont icon-change" style="color: #84a7d9;"></i></p>
                <p>积分兑换</p>
            </a>
        </li>
        <li class="flexitemv">
            <a href="" class="flexitemv center">
                <p class="flex center icon"><i class="iconfont icon-card" style="color: red;"></i></p>
                <p>银行卡绑定</p>
            </a>
        </li>
    </ul>
</div>

@include('index._footer')

@include('index._notice')

<script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
<script src="/plugins/mobile/layer.js"></script>
<script src="/js/common.js"></script>
</body>
</html>