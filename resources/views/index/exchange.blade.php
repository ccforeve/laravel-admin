@include('index._header')

<body id="exchange" class="flexv">
<div id="common-header" class="flex">
    <a href="javascript:history.back()" class=" cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>积分兑换</h1>
    <a href="{{ route('index.record') }}" class="cBtn exchange"><span>记录</span></a>
</div>
<div class="flexitemv main">
    <div class="flex switch-btn">
        <p class="flexitemv center tab-btn active" data-index="0"><span>付费套装积分兑换</span></p>
        <p class="flexitemv center tab-btn" data-index="1"><span>免费领取积分兑换</span></p>
    </div>
    <div class="flexitemv scroll content">
        <div class="show-item on">
            <div class="flexv centerv">
                <div class="flexv part">
                    <h2>积分兑换银行卡提现</h2>
                    <div class="flexitemv centerv text">
                        <div class="flexitemv centerv">
                            <p class="a">1积分兑换1元 单次最多<span class="green">200</span></p>
                            <strong class="green">{{ $suit }}</strong>
                            <p class="c">可兑换积分</p>
                        </div>
                        <a class="flex center green" data-url="{{ route('index.exchange_operation') }}" onclick="integral(this,{{ $suit }},2);">立即兑换</a>
                    </div>
                </div>
                <div class="flexv part">
                    <h2>积分兑换商品</h2>
                    <div class="flexitemv centerv text">
                        <h3 class="flexitemv center">购买时可使用积分消费1积分等于1元钱</h3>
                        <a href="/" class="flex center green">前往购买</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="show-item">
            <div class="flexv centerv">
                <div class="flexv part">
                    <h2>积分兑换银行卡提现</h2>
                    <div class="flexitemv centerv text">
                        <div class="flexitemv centerv">
                            <p class="a">1积分兑换1元 单次最多<span class="green">200</span></p>
                            <p class="b">每月兑换一次，@if(empty($exchange_time))<span class="green">未兑换过</span>@else下次兑换<span class="green">{{ $exchange_time }}</span>@endif</p>
                            <strong class="green">{{ $free }}</strong>
                            <p class="c">可兑换积分</p>
                        </div>
                        <a href="javascript:;" class="flex center green" data-url="{{ route('index.exchange_operation') }}" onclick="integral(this,{{ $free }},1);">立即兑换</a>
                    </div>
                </div>
                <div class="flexv part">
                    <h2>积分兑换商品</h2>
                    <div class="flexitemv center text">
                        <h3>免费领取推广奖励积分暂不支持兑换商品</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="/plugins/mobile/layer.js"></script>
<script src="/js/checkform.js"></script>
<script src="/js/functions.js"></script>
<script src="/js/common.js"></script>
<script src="/js/geo.js"></script>
<script src="http://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
<script>
    $('a').last().hide();

    var switchBtn = $('.switch-btn').find('p'),
        showItem = $('.show-item');
    switchBtn.click(function () {
        $(this).addClass('active').siblings().removeClass('active');
        showItem.removeClass('on');
        for (var i = 0; i < showItem.length; i++) {
            if (i == $(this).attr('data-index')) {
                showItem.eq(i).addClass('on');
            }
        }
    });
    var trottle = null;
    function integral(th,integral,type) {
        layer.open({
            content: '<div class="sl"><p class="sl-item">可使用积分<span class="total-num">'+integral+'</span></p><input type="number" name="integral" value="" placeholder="请输入本次使用积分" class="sl-item"></div>'
            , skin: 'footer'
            , btn: ['确定', '取消']
            , yes: function (index, el) {
                console.log(el);
                var num = el.querySelector('input[name=integral]').value;
                if(num<=0){
                    showMsg('兑换不能小于1积分');return false;
                }else {
                    //异步请求银行卡提现
                    var url = $(th).attr('data-url');
                    if(!trottle) {
                        $.post(url, { integral: num,type:type,_token:"{{ csrf_token() }}" }, function(ret){
                            if(ret.state == 0) {
                                showMsg(ret.error, 1);
                            } else {
                                showMsg(ret.error);
                            }
                            if(ret.url){
                                setTimeout(function (){window.location.href = ret.url;},1000);
                            } else{
                                setTimeout(function (){window.location.reload();},1000);
                            }
                        });
                        trottle = true;
                    }
                }
            }
            , anim: 'up'
            , success: function (el) {
                var totalNum = Number(el.querySelector('.total-num').innerText),
                    oInput = el.querySelector('input[name=integral]');
                oInput.focus();
                oInput.addEventListener('input', function () {
                    var currentNum = Number(oInput.value);
                    if (currentNum > integral) {
                        oInput.blur();
                        oInput.value = integral;
                    }
                    if (currentNum > 200) {
                        oInput.blur();
                        oInput.value = 200;
                        showMsg('最大可兑换积分200',0,1500);
                    }
                })
            }
        });
    };
</script>
</body>
</html>