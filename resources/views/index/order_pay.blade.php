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
                    <div class="name"><span>{{ session('address')['name'] }}</span>&nbsp;&nbsp;<span>{{ session('address')['phone'] }}</span></div>
                    <div class="sites"><span>{{ session('address')['province'] }}{{ session('address')['detail'] }}</span></div>
                </div>
                <input type="hidden" name="address_id" value="{{ session('address')['id'] }}" data-rule="*" data-errmsg="请选择地址">
            @elseif($address)
                <div class="flexitemv centerh message">
                    <div class="name"><span>{{ $address->name }}</span><span>{{ $address->phone }}</span></div>
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
                            @case(3)
                                免费体验
                                @break
                        @endswitch
                        @if($order->type != 2)
                            / 邮费：¥ {{ number_format($order->orderAttr['postage']) }}
                        @endif
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

            @if($order->product_type != 2)
                @if(count($order->orderAttr->sepcs))
                    <div class="flex centerv item">
                        <p>规格</p>
                        <span class="spec-price">@if(count($order->orderAttr->sepcs)){{ $order->orderAttr->specs->name }} / ¥ {{ number_format($order->orderAttr->specs->price) }} @else 无可选规格 @endif</span>
                    </div>
                @endif
                <div class="flex centerv item">
                    <p>包装费用</p>
                    <span class="pack-price">@if($order->orderAttr->packing == 1) 不包装 @else ¥ 5.00 @endif</span>
                </div>
            @endif

            <div class="flex centerv item">
                <p>配送费用</p>
                <span class="post-price">@if($order->product_type == 2) 包邮 @else ￥15.00 @endif</span>
                @if($order->product_type == 1)<input type="hidden" name="postarea" value="0">@endif
            </div>

            @if($integral)
                <div class="flex centerv item" id="integral">
                    <p>使用积分</p>
                    <span></span>
                    <input type="hidden" name="use_integral" value=""><!---------使用的积分----------->
                    <i class="iconfont icon-go" style="position: relative; top: 1px;"></i>
                </div>
            @endif

            <input type="hidden" id="original_price" value="{{ $order->original_price }}" />
            <!-- 偏远邮费 -->
            @if(session('extra_postage'))
                <input type="hidden" id="postage" value="{{ session('extra_postage') }}">
            @else
                <input type="hidden" id="postage" value="{{ $extra_postage }}">
            @endif

            <div class="flex centerv item special">
                <p>合计费用：<strong class="final-price" id="pay_price" price=''>¥ </strong></p>
            </div>
        </div>

        <input type="hidden" name="pay_price" id="post_pay_price">

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
    var original_price = $('#original_price').val();
    var postage = 0;
    if({{ $order->product_type }} == 1) {
        //偏远地区+10元
        var postage = Number($('#postage').val()),
            postPrice = $('.post-price');
        if (postage == 10) {
            postPrice.html('￥25（偏远地区加收10元）');
            $('.post-price').attr('price', 10);
            $('input[name=postarea]').val(10);
        }
    }

    document.querySelector('#post_pay_price').value = Number(Number(original_price) + Number(postage)).toFixed(2);
    document.querySelector('#pay_price').innerText = '¥ ' + Number(Number(original_price) + Number(postage)).toFixed(2);

    /*积分使用弹出层效果*/
    if (integral) {
        integral.addEventListener('click', function () {
            if(document.querySelector('.post-price')) {
                var postPrice = Number(document.querySelector('.post-price').getAttribute('price'));
            }
            var totalPrice = Number(original_price) + Number(postage);
            layer.open({
                content: '<div class="sl"><p class="sl-item">可使用积分<span class="total-num">{{$integral}}</span></p><input type="number" name="integral" value="" placeholder="请输入本次使用积分" class="sl-item"></div>'
                , skin: 'footer'
                , btn: ['确定', '取消']
                , yes: function (index, el) {
                    var num = el.querySelector('input[name=integral]').value;

                    integral.querySelector('span').innerText = num;
                    integral.querySelector('input').value = num;
                    if((totalPrice - Number(num))==0){
                        document.querySelector('.final-price').innerText = '¥ 0.01';
                        document.querySelector('#post_pay_price').value = '0.01';
                    }else{
                        document.querySelector('.final-price').innerText = '¥ ' + (totalPrice - Number(num)).toFixed(2);
                        document.querySelector('#post_pay_price').value = (totalPrice - Number(num)).toFixed(2);
                    }
                    document.querySelector('.final-price').setAttribute('price',(totalPrice - Number(num)).toFixed(2));


                    layer.close(index);
                }
                , anim: 'up'
                , success: function (el) {
                    var totalNum = Number(el.querySelector('.total-num').innerText),
                        oInput = el.querySelector('input[name=integral]');

                    oInput.focus();
                    oInput.addEventListener('input', function () {
                        var currentNum = Number(oInput.value);

                        if (currentNum > totalPrice) {
                            oInput.blur();
                            oInput.value = totalPrice;
                            showMsg('不可大于商品价格');
                        } else if (currentNum > totalNum) {
                            oInput.blur();
                            oInput.value = totalNum;
                            showMsg('不可大于现有积分');
                        } else if(currentNum < 1) {
                            oInput.value = 1;
                            showMsg('积分不可为0');
                        }
                    })
                }
            });
        });
    }
    /*积分使用弹出层效果结束*/

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