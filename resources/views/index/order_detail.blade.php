@include('index._header')

<body id="order-detail" class="flexv">
<div id="common-header" class="flex">
    <a href="javascript:history.back()" class="cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>订单详情</h1>
</div>
<div class="flexitemv scroll main">
    <div class="flex centerv address">
        <i class="iconfont icon-address"></i>
        <div class="flexitemv content">
            <p class="flex"><span class="uname">{{ $order->address->name }}</span><span class="tel">{{ $order->address->phone }}</span></p>
            <span class="intro">{{ $order->address->province }}{{ $order->address->detail }}</span>
        </div>
    </div>
    <div class="tips">
        <div class="flex centerv item">
            <p>订单号</p>
            <span>{{ $order->number }}</span>
        </div>
        @if(count($order->logistics))
            <div class="flex item" style="height: auto;">
                <a href="javascript:;" data-url="@if($logistics.status == 1) {:url('User/islogistics',['id'=>$res.id])} @endif" class="flexitem centerv @if($logistics.status == 1) logistics @endif">
                    <i class="iconfont icon-wuliu" style="color: green;"></i>
                    <div class="flexitemv text" style="padding: 10px 5px 10px 10px;">
                        @if($logistics.status == 1)
                            <p style="color: green;padding-bottom: 5px;">{{ $logistics['data'][0]['context'] }}</p>
                            <time style="color: #999;">{{ $logistics['data'][0]['time'] }}</time>
                        @else
                            <p style="color: green;padding-bottom: 5px;">{{ $logistics['message'] }}</p>
                        @endif
                    </div>
                    <i class="iconfont icon-go"></i>
                </a>
            </div>
        @endif
    </div>
    <div class="flex goods-item">
        <a href="{{ route('index.product_details', $order->product_id) }}" class="flexitem">
            <div class="img"><img src="{{ config('app.index_image').$order->product->photo[2] }}" alt=""></div>
            <div class="flexitemv content">
                <h2>{{ $order->product->name }}</h2>
                <p>
                    @if($order->product_type == 1)
                        免费领取
                    @else
                        常规套装
                    @endif
                    @if($order->product_type != 2)/
                        @if($order->orderAttr['packing']) 正常包装 @else 不包装 @endif  /  邮费 ¥15
                        @if($order->orderAttr['postage_area'])  /  偏远地区配送费 ¥10 @endif
                    @endif
                </p>
                <span>¥ {{ number_format($order->product->price, 2) }}</span>
            </div>
        </a>
    </div>
    <div class="details">
        <div class="flex centerv item">
            <p>商品价格</p>
            <span>¥ {{ number_format($order->product->price, 2) }}</span>
        </div>

        @if($order->product_type != 2)
            @if($order->orderAttr->packing)
                <div class="flex centerv item">
                    <p>包装费用</p>
                    <span>¥ 5.00</span>
                </div>
            @endif
            <div class="flex centerv item">
                <p>配送费用</p>
                <span>@if($order->orderAttr->postage_area)¥ 25.00(偏远地区加10元) @else ¥ 15.00 @endif</span>
            </div>
            @if($order->orderAttr['spec'])
                <div class="flex centerv item">
                    <p>商品规格</p>
                    <span>{{ $order->orderAttr->specs->name }} ¥{{ $order->orderAttr->specs->price }}</span>
                </div>
            @endif
        @endif
        @if($order->use_integral)
            <div class="flex centerv item">
                <p>使用积分</p>
                <span>{{ $order->use_integral }}</span>
            </div>
        @endif
        <div class="flex centerv item special">
            <p>合计费用：<strong>¥ {{ number_format($order->pay_price, 2) }}</strong></p>
        </div>
    </div>
    @if($order->status == 0)
        <div class="flex center pay">
            <a href="javascript:" data-url="{{ route('order_detail_pay',['order_id'=>$order->id,'pay_type'=>2]) }}" class="flex center pay__alipay pay__btn">支付宝支付</a>
            <a href="javascript:" data-url="{{ route('order_detail_pay',['order_id'=>$order->id,'pay_type'=>1]) }}" class="flex center pay__weipay pay__btn">微信支付</a>
        </div>
    @endif
</div>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="/js/functions.js"></script>
<script>
    $('a').last().hide();

    $('.logistics').click(function () {
        var url = $(this).attr('data-url');
        window.location.href = url;
    });

    $('.pay__btn').click(function () {
        var url = $(this).attr('data-url');
        $.get(url,function (ret) {
            if(ret.state == 0) {
                window.location.href = ret.url;
            } else {
                showMsg(ret.error, 0, 1500);
                setTimeout(function () {
                    window.location.reload();
                }, 1500)
            }
        })
    })
</script>
</html>