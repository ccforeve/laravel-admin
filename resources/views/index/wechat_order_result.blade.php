<title>支付结果</title>
@include('index._header')

<style>
    .back a { display: block; text-align: center; background: #fff; width: 100px; line-height: 40px; border: 1px solid #ccc; border-radius: 5px; font-size: 1.2em; padding: 6px 12px}
</style>

<body id="result" class="flexv">
<div id="common-header" class="flex">
    <a href="/index" class=" cBtn back"><i class="iconfont icon-index"></i></a>
    <h1>支付结果</h1>
    <a href="/user" class="cBtn ucenter"><i class="iconfont icon-center"></i></a>
</div>
<div class="flexitemv scroll main">
    @if($state == 'ok')
        <div class="flexv center content">
            <!--<i class="iconfont icon-wpay"></i>-->
            <!--<p>您已支付成功</p>-->
            <img src="/index_images/paykf.jpg" alt="">
        </div>
    @elseif($state == 'cancel' || $state == 'fail')
        <div class="flexv center content">
            <i class="error iconfont icon-error"></i>
            <p style="color: red; padding: 0 0 5px 0;">取消支付或支付失败</p>
            @if($order->activity)
                <div class="count-down" data-end="{{ \Carbon\Carbon::parse($order->updated_at)->addMinutes(15)->timestamp }}" style="font-size: 16px">
                    请在<span class="min">00</span>:<span class="sec">00</span>内完成支付
                </div>
            @endif
            <img src="/index_images/kefu.jpg" style="width: 40%; margin-bottom: 5px;">
            <span class="flex" style="color: #000; font-size: 1.125em; margin-bottom: 5px;">长按识别二维码</span>
            <span class="flex" style="color: #000; font-size: 1.125em; margin-bottom: 5px;">联系客服咨询购买</span>
        </div>
    @endif

    <div class="flex center back" style="margin: 15px"><a href="/index">返回首页</a></div>

    <div class="tips">
        <div class="text">
            <h3><span>温馨提示</span></h3>
            <p>订单支付成功后，我们会在1-2个工作日给您发货，您 的订单物流信息请登录用户中心跟踪。</p>
            <p>支付成功后，如果您是第一次购买，系统会自动给您创建账号并发送信息到您填写的收件人手机上，您可以通过该帐号登录查看订单进度。</p>
        </div>
    </div>
</div>

<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js" language="JavaScript"></script>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
<script src="https://cdn.bootcss.com/moment.js/2.19.0/moment.min.js"></script>
<script src="/js/time-out.js"></script>
</body>
<script>
    $('a').last().hide();
</script>
</html>