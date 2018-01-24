@include('index._header')

<style>
    #order-pay select {
        display: block;
        padding: 0;
        height: 35px;
        width: 25%;
    }
</style>

<body id="order-pay" class="flexv">
    <div id="common-header" class="flex">
        <a href="javascript:history.back()" class="cBtn back"><i class="iconfont icon-back"></i></a>
        <h1>订单付款</h1>
    </div>
    <form class="flexitemv scroll main" id="form" action="{{ route('index.order_pay', $order->id) }}">
        {{ csrf_field() }}

        <a href="{{ route('address.index') }}" class="flex address">
            <i class="flex center iconfont icon-dizhi" style="color: rgb(142, 204, 85);"></i>
            @if(session('address'))
                <div class="flexitemv centerh message">
                    <div class="name"><span>{{ session('address')['name'] }}</span>&nbsp;&nbsp;&nbsp;&nbsp;<span>{{ session('address')['phone'] }}</span></div>
                    <div class="sites"><span>{{ session('address')['province'] }}{{ session('address')['detail'] }}</span></div>
                </div>
                <input type="hidden" name="address_id" value="{{ session('address')['id'] }}" data-rule="*" data-errmsg="请选择地址">
            @elseif($address)
                <div class="flexitemv centerh message">
                    <div class="name"><span>{{ $address->name }}</span>&nbsp;&nbsp;&nbsp;<span>{{ $address->phone }}</span></div>
                    <div class="sites"><span>{{ $address->province }}{{ $address->detail }}</span></div>
                </div>
                <input type="hidden" name="address_id" value="{{ $address->id }}" data-rule="*" data-errmsg="请选择地址">
            @else
                <span class="flexitem center">请点击选择收货地址</span>
            @endif
            <i class="flex center iconfont icon-go" style="color: #ccc;"></i>
        </a>

        <div class="flex goods-item">
            <a href="javascript:;" class="flexitem">
                <div class="img"><img src="{{ config('app.index_image').$order->product['photo'][2] }}" alt=""></div>
                <div class="flexitemv content">
                    <name style="font-size: 16px">{{ $order->product['name'] }}</name>
                    <p>
                        @switch($order->product_type)
                            @case(1)
                                免费领取
                                @break
                            @case(2)
                                标准套装
                                @break
                        @endswitch
                    </p>
                    <p class="flex p"><span>¥ {{ number_format($order->product_price, 2) }}</span><em>x1</em></p>
                </div>
            </a>
        </div>
        <div class="part2">
            <div class="flex centerv item">
                <p>商品价格</p>
                <span class="goods-price">¥ {{ number_format($order->product_price, 2) }}</span>
            </div>

            @if($order->activity)
                <div class="flex centerv item">
                    <p>活动价格</p>
                    <span class="goods-price">¥ {{ number_format($order->original_price, 2) }}</span>
                </div>
            @endif

            <input type="hidden" name="pay_price" value="{{ $order->original_price }}" />

            <div class="flex centerv item special">
                <p>合计费用：<strong class="final-price">¥ {{ number_format($order->original_price, 2) }}</strong></p>
            </div>
        </div>

        <div class="flex center pay">
            <a href="javascript:" class="flex center pay__alipay pay__btn alipay">支付宝支付</a>
            <a href="javascript:" class="flex center pay__weipay pay__btn wechatpay">微信支付</a>
        </div>
    </form>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="/plugins/mobile/layer.js"></script>
<script src="/js/checkform.js"></script>
<script src="/js/functions.js"></script>
<script src="/js/common.js"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script>
    new checkForm({
        form : '#form',
        btn : '.alipay',
        error : function (e,msg){
            showMsg(msg,0,2000);
        },
        complete : _.throttle(function (form){
            showProgress('正在支付...');
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            var obj = {
                name  :  'pay_type',
                value :   '2'
            };
            datas.push(obj);
            $.post(url,datas,function(ret){
                hideProgress();
                if(ret.state == 0){
                    showMsg(ret.error, 1);
                } else {
                    showMsg(ret.error);
                }
                if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
            },'json');
        }, 5000, { 'trailing': false })
    });

    new checkForm({
        form : '#form',
        btn : '.wechatpay',
        error : function (e,msg){
            showMsg(msg,0,2000);
        },
        complete : _.throttle(function (form){
            showProgress('正在支付...');
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            var obj = {
                name  :  'pay_type',
                value :   '1'
            };
            datas.push(obj);
            $.post(url,datas,function(ret){
                console.log(ret);
                hideProgress();
                if(ret.state == 0){
                    showMsg(ret.error, 1);
                } else {
                    showMsg(ret.error);
                }
                if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
            },'json');
        }, 5000, { 'trailing': false })
    });
</script>
</html>