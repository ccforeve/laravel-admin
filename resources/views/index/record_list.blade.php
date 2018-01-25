@include('index._header')

<body id="record" class="flexv">
<div id="common-header" class="flex">
    <a href="javascript:history.back()" class=" cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>兑换记录</h1>
</div>
<div class="flexitemv scroll main">
    @foreach($use_integrals as $integral)
        <div class="flex record-item">
            @if($integral->order_id)
            <a href="{{ route('index.order_detail', $integral->order_id) }}" class="flexitem centerv">
            @endif
                <div class="flexitemv centerh intro">
                    <p>付款推广积分兑换@if($integral->mean == 1) <color style="color: blue">商品</color> @else <color style="color: blue">提现</color> @endif</p>
                    <span>{{ $integral->created_at }}</span>
                </div>
                <div class="flexv center text">
                    <p>{{ $integral->use_integral }}</p>
                    <span>
                        @if($integral->status == 1)
                            <color style="color: green">兑换完成</color>
                        @elseif($integral->status == 2)
                            <color style="color: red">兑换失败</color>
                        @else
                            <color style="color: grey">兑换待完成</color>
                        @endif
                    </span>
                </div>
                @if($integral->order_id)<i class="iconfont icon-go"></i>@endif
            </a>
        </div>
    @endforeach
</div>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js" language="JavaScript"></script>
<script>
    $('a').last().hide();
</script>
</html>