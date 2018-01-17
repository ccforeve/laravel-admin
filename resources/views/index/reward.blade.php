@include('index._header')

<body id="reward" class="flexv">
<div id="common-header" class="flex">
    <a href="{{ route('index.user') }}" class=" cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>推广奖励</h1>
    <a href="{{ route('index.exchange') }}" class="cBtn exchange"><span>兑换</span></a>
</div>
<div class="flexitemv scroll main">
    <div class="flex details">
        <div class="flexitemv centerv part">
            <p class="a">{{ $suit }}</p>
            <p class="b">付费套装推广奖励</p>
            <p class="c">@if($user->type) 30 @else 20 @endif 积分 / 套</p>
            <a href="{{ route('index.reward_list', ['type'=>2,'status'=>0]) }}" class="d">查看记录 ></a>
        </div>
        <div class="flexitemv centerv part">
            <p class="a">{{ $free }}</p>
            <p class="b">免费领取推广奖励</p>
            <p class="c">1 积分 / 套</p>
            <a href="{{ route('index.reward_list', ['type'=>1,'status'=>0]) }}" class="d">查看记录 ></a>
        </div>
    </div>
    <div class="flex centerh switch-btn">
        <div class="flex">
            <p class="flex center tab-btn active" data-index="0">推广链接</p>
            <p class="flex center tab-btn" data-index="1">
                <a href="{{ route('index.extension_page', session('user_id')) }}">推广二维码</a>
            </p>
        </div>
    </div>
    <div class="show">
        <div class="show-item on">
            <p class="url" id="clipText">{{ route('index', session('user_id')) }}</p>
            <div class="flex centerh operation">
                <a href="javascript:;" class="flex center clip" id="clip" data-text="{{ route('index', session('user_id')) }}">复制链接</a>
                <a href="{{ route('index', session('user_id')) }}" class="flex center">进入页面分享</a>
            </div>
        </div>
        <div class="show-item">
            <div class="flex centerh img">
                <img src="" alt="">
            </div>
            <p class="intro">微信中长按二维码可保存</p>
        </div>
    </div>
    <div class="tips">
        <h2>推广积分如何使用？</h2>
        <p>推广积分可兑换等额现金，也可以在购买产品时作为等额现金消费</p>
        <h2>如何推广？</h2>
        <p>
            系统提供两种推广方式，链接形式：你可以复制链接发送给您的伙伴，也可以进入链接页面通过微信分享给您的伙伴。二维码形态：微信中长按保存二维码
            <a href="{{ route('index.reward_rule') }}" style="color:blue;">详细推广介绍</a>
        </p>
    </div>
</div>
<script src="http://cdn.bootcss.com/jquery/2.2.4/jquery.min.js"></script>
<script src="/plugins/clipboard.js"></script>
<script src="/js/functions.js"></script>
<script>

    if (Clip.isSupported()) {
        var clipBoard = new Clip('#clip', {
            text: function () {
                return document.querySelector('#clip').getAttribute('data-text');
            }
        });
        clipBoard.on('success', function (e) {
            showMsg('复制成功',1);
        });
        clipBoard.on('error', function(e) {
            showMsg(e.action + ' ' + e.trigger, 0, 'body', 100000);
        });
    } else {
        showMsg('此手机不支持复制功能，请手动复制', 0, 2000);
    }
</script>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script>
    $('a').last().hide();
</script>
</html>