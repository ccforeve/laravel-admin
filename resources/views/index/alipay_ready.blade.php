@include('index._header')
<body class="flex">
<div id="ali" class="flexitemv centerh">
    <div class="top">
        <i class="flex endh iconfont icon-arrow"></i>
        <p style="margin: 0 10px">请点击右上角"<i class="iconfont icon-share"></i>"选择在浏览器中打开以完成支付</p>
    </div>
    <div class="flexitemv main center">
        <img src="/index_images/background.png">
    </div>
    <div class="flex center bottom">
        <a href="/index" class="flex center">返回首页</a>
    </div>
</div>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
<script>
    $('a').last().hide();

    @if(strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false)

    @else
        window.location.href = "{{ route('index.pay', ['type' => 2, 'order_pay' => request()->order_pay]) }}";
    @endif
</script>
</html>