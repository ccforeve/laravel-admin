@include('index._header')

<style>
    #order .bottom, #extension .bottom{
        height:68px;
    }
</style>

<body id="extension" class="flexv">
<div id="common-header" class="flex">
    <a href="{{ route('index.reward') }}" class=" cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>推广记录</h1>
    <a href="javascript:;" class="cBtn filter"><span>筛选</span></a>
</div>
<div class="flexitemv scroll main">
    <div class="flex tab">
        <a href="{{ route('index.reward_list', ['type'=>request()->type,'status'=>0]) }}" class="flexitemv center {{ active_class(if_route_param('status', 0), 'on') }}"><span>全部</span></a>
        <a href="{{ route('index.reward_list', ['type'=>request()->type,'status'=>1]) }}" class="flexitemv center {{ active_class(if_route_param('status', 1), 'on') }}"><span>待确认</span></a>
        <a href="{{ route('index.reward_list', ['type'=>request()->type,'status'=>3]) }}" class="flexitemv center {{ active_class(if_route_param('status', 3), 'on') }}"><span>退款扣除</span></a>
        <a href="{{ route('index.reward_list', ['type'=>request()->type,'status'=>2]) }}" class="flexitemv center {{ active_class(if_route_param('status', 2), 'on') }}"><span>已获得</span></a>
        <!--<a href="javascript:;" class="flexitemv center {eq name='Think.get.type' value='3'}on{/eq}"><span>图文统计</span></a>-->
    </div>
    <ul class="list">
        @if(count($reward_list))
            @foreach($reward_list as $list)
                <li>
                    <div class="flex centerv title">
                        <p class="type">@if($list->order->product_type == 2) 付费套餐 @else 免费领取 @endif</p>
                        <p class="state">
                            @switch($list->order->status)
                                @case(0)
                                    待付款
                                    @break
                                @case(1)
                                    已付款
                                    @break
                                @case(2)
                                    已退款
                                    @break
                           @endswitch
                        </p>
                    </div>
                    <div class="flex goods-item">
                        <div class="img">
                            <a href="{{ route('index.product_details', $list->order->product_id) }}">
                                <img src="{{ config('app.index_image').$list->order->product->photo[2] }}" alt="">
                            </a>
                        </div>
                        <div class="flexitemv content">
                            <h2>{{ $list->order->product->name }}</h2>
                            <p>
                                @switch($list->order->product_type)
                                    @case(1)
                                        免费领取 @break
                                    @case(2)
                                        常规套装 @break
                                @endswitch
                                @if($list->order->product_type == 1)
                                    /
                                    @if($list->order->orderAttr->spec)
                                        {{ $list->order->orderAttr->specs->name }} - {{ $list->order->orderAttr->specs->price }}元
                                    @endif
                                    /
                                    @if($list->order->orderAttr->packing == 2)
                                        正常包装 ¥ 5.00
                                        @else
                                        不包装
                                    @endif
                                @endif
                                  /  邮费 ¥15
                            </p>
                            <span>¥ {{ $list->order->product->price }} </span>
                        </div>
                    </div>
                    <div class="flex centerv bottom">
                        <div class="flexitemv tips">
                            <p>合计：<span>¥ {{ number_format($list->order->pay_price, 2) }}</span> &nbsp;&nbsp;推广积分：<span>{{ $list->integral }}</span></p>
                            <time>{{ $list->order->created_at }}</time><br/>
                            <span style="padding-bottom: 5px">购买人：{{ $list->order->user->nickname }}</span>
                        </div>
                        <div class="flex operation">
                            @if($list->is_status != 0)
                                <a href="tel:{{ $list->order->user->phone }}" class="active">联系买家</a>
                            @endif
                        </div>
                    </div>
                </li>
            @endforeach
        @else
            <div style="margin: 0 auto;text-align: center;margin-top: 45%;font-size:1.25rem;color: #888">暂无记录~</div>
        @endif
    </ul>
</div>

<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="/plugins/mobile/layer.js"></script>
<script src="/js/common.js"></script>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
<script>
    $('a').last().hide();

    /*推广记录页*/
    var extension = document.querySelector('#extension');

    if (extension) {
        /*筛选弹出层效果*/
        extension.querySelector('.filter').addEventListener('click', function () {
            layer.open({
                content: '<div class="sl"><a href="{{route('index.reward_list', ['type'=>0,'status'=>0])}}" class="sl-item">全部推广</a><a href="{{route('index.reward_list', ['type'=>2,'status'=>0])}}" class="sl-item">付费套装推广</a><a href="{{route('index.reward_list', ['type'=>1,'status'=>0])}}" class="sl-item">免费领取推广</a></div>'
                ,skin: 'footer'
                ,btn: ['取消']
                ,anim: 'up'
            });
        });
    }
    /*推广记录页结束*/
</script>
</html>