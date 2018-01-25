@include('index._header')

<style>
    .flexitemv .tab{
        background: #fff;
    }
</style>

<body id="order" class="flexv">
<div id="common-header" class="flex">
    <a href="{{ route('index.user') }}" class="cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>我的订单</h1>
</div>
<div class="flexitemv scroll main">
    <div class="flex tab">
        <a href="{{ route('index.order_list', 2) }}" class="flexitemv center {{ active_class(if_route_param('status', 2), 'on') }}"><span>全部</span></a>
        <a href="{{ route('index.order_list', 0) }}" class="flexitemv center {{ active_class(if_route_param('status', 0), 'on') }}"><span>待付款</span></a>
        <a href="{{ route('index.order_list', ['status'=>1,'confirm'=>0,'']) }}" class="flexitemv center {{ active_class((if_route_param('status', 1)&&if_route_param('confirm', 0)), 'on') }}"><span>待发货</span></a>
        <a href="{{ route('index.order_list', ['status'=>1,'confirm'=>1]) }}" class="flexitemv center {{ active_class(if_route_param('confirm', 1), 'on') }}"><span>已发货</span></a>
        <a href="{{ route('index.order_list', ['status'=>1,'confirm'=>2]) }}" class="flexitemv center {{ active_class(if_route_param('confirm', 2), 'on') }}"><span>已完成</span></a>
    </div>
    <ul class="list">
        @if($order_lists->isNotEmpty())
            @foreach($order_lists as $list)
            <li>
                <div class="flex centerv title">
                    <p class="type">
                        @switch($list->product_type)
                            @case(1)
                                免费领取
                                @break
                            @case(2)
                                付费套餐
                                @break
                            @case(3)
                                体验产品
                                @break
                        @endswitch
                    </p>
                    <p class="state">
                        @if($list->is_status)
                            <color style="color: red;">
                                @if($list->is_status == 1)
                                    申请退款
                                @elseif($list->is_status == 2)
                                    收货退款
                                @elseif($list->is_status == 3)
                                    取消订单
                                @endif
                            </color>
                        @else
                            @if($list->status == 1) 已付款 @else 待付款 @endif
                        @endif
                    </p>
                </div>
                <div class="flex goods-item">
                    <a href="{{ route('index.order_detail', $list->id) }}" class="flexitem">
                        <div class="img"><img src="{{ config('app.index_image').$list->product['photo'][2] }}" alt=""></div>
                        <div class="flexitemv content">
                            <h2>{{ $list->product['name'] }}</h2>
                            @if($list->activity)
                                <p>
                                    @if($list->product_type == 1)
                                        免费领取
                                    @elseif($list->product_type == 2)
                                        常规套装
                                    @elseif($list->product_type == 3)
                                        免费体验
                                    @endif
                                      /  活动订单
                                </p>
                            @else
                                <p>
                                    @if($list->product_type == 1)
                                        免费领取
                                    @elseif($list->product_type == 2)
                                        常规套装
                                    @elseif($list->product_type == 3)
                                        体验商品
                                    @endif
                                    @if($list->product_type != 2)
                                        @if(count($list->orderAttr->specs))  /  {{ $list->orderAttr->specs->name }} ¥{{ $list->orderAttr->specs->price }} @endif /
                                        @if($list->orderAttr['packing'])正常包装 ¥5 @else 不包装 @endif  /  邮费 ¥15
                                        @if($list->orderAttr['postage_area'])/  偏远地区配送费 ¥10 @endif
                                    @endif
                                </p>
                            @endif
                            <span>¥ {{ number_format($list->product['price'], 2) }}</span>
                        </div>
                    </a>
                </div>

                <div class="flex centerv bottom">
                    <div class="flexitemv tips">
                        <p>合计：<span>¥ {{ number_format($list->pay_price, 2) }}</span></p>
                        <time>{{ $list->create_at }}</time>
                    </div>
                    <div class="flex centerv operation">
                        <a href="{{ route('index.order_detail', $list->id) }}" class="active" style="border: 1px #666 solid;color:#666">订单详情</a>
                        @if($list->status == 0 && $list->is_status == 0)
                            <a href="javascript:;" class="active update" data-url="{{ route('index.order_operation',['order'=>$list->id,'type'=>5,'msg'=>'取消订单']) }}">取消订单</a>
                            <a class="flex time-out" href="{{ route('index.order_detail', $list->id) }}" style="color: #27e;border-color: #27e;">
                                去支付&nbsp;
                                @if($list->activity)
                                    <div class="count-down" data-end="{{ $list->updated_at->timestamp + 900 }}" style="margin-top: 1px">
                                        <span class="min">00</span>:<span class="sec">00</span>
                                    </div>
                                @endif
                            </a>
                        @endif
                        @if($list->is_status == 0 && $list->status == 1)
                            @if($list->confirm == 0)
                                <a href="javascript:;" class="active update" data-url="{{ route('index.order_operation',['order'=>$list->id,'type'=>2,'msg'=>'申请退款']) }}">申请退款</a>
                            @endif
                            @if($list->confirm == 1)
                            <!--<a href="javascript:;" class="logistics" data-url="{:url('User/islogistics',['id'=>$v.id])}">查看物流</a>-->
                                <a href="javascript:;" class="active update" data-url="{{ route('index.order_operation',['order'=>$list->id,'type'=>3,'msg'=>'售后退款']) }}">售后退款</a>
                                <a href="javascript:;" class="active update" data-url="{{ route('index.order_operation',['order'=>$list->id,'type'=>4,'msg'=>'确认收货']) }}" style="color: #27e;border-color: #27e;">确认收货</a>
                            @endif
                        @endif
                        @if($list->is_status == 3)
                            <a href="javascript:;" class="active update" data-url="{{ route('index.order_operation',['order'=>$list->id,'type'=>1,'msg'=>'删除订单']) }}">删除订单</a>
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
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="/plugins/layer.js"></script>
<script src="/js/functions.js"></script>
<script src="https://cdn.bootcss.com/moment.js/2.19.0/moment.min.js"></script>
<script src="/js/time-out.js"></script>
<script>
    $('a').last().hide();

    $('.logistics').click(function () {
       var url = $(this).attr('data-url');
       window.location.href = url;
    });

    $('.update').click(function () {
        var url = $(this).attr('data-url'),
            msg = $(this).text();
        layer.confirm('您确定要'+msg+'吗？', {
            btn: ['确定','取消'] //按钮
        }, function(){
            $.get(url,function (ret) {
                console.log(ret);
                layer.msg(ret.error, {icon: 1});
                setTimeout(function () {
                    window.location.reload();
                },1000)
            })
        });
    })
</script>
</html>