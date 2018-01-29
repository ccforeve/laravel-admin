
<link rel="stylesheet" href="/css/icon.css">
<link rel="stylesheet" href="/css/main.css">
<link rel="stylesheet" href="/plugins/skin/default/layer.css">
<div id="right">
	<div class="boxes order">
		<div class="flex centerv rtitle"><i class="flexitem"></i><span class="flex center">订单信息</span></div>
			<div class="infotable">
				<table>
					<tbody>
						<tr>
							<td width="80">订单号</td>
							<td>{{ $order->number }}</td>
							<td width="80">下单时间</td>
							<td>{{ $order->created_at }}</td>
							<td width="80">订单状态</td>
							<td>
								@if($order->status == 1)
									已支付
								@elseif($order->status == 2)
									已退款
								@else
									未支付
								@endif
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="flex centerv rtitle"><i class="flexitem"></i><span class="flex center">购买商品</span></div>
			<div class="infotable">
				<table>
					<tbody>
					<tr>
						<td width="80">商品名称</td>
						<td>{{ $order->product->name }}</td>
					</tr>
					</tbody>
				</table>
				<table>
					<thead>
						<tr>
							<th>商品图</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>
								<img src="{{ config('app.index_image').$order->product->photo[0] }}" width="150"/>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="flex centerv rtitle"><i class="flexitem"></i><span class="flex center">费用统计</span></div>
			<div class="flexv listrows">
				@if($order->activity == 0 && $order->product_type != 2)
					@if($order->orderAttr->spec)
						<div class="flex endh">
							<em>{{ $order->orderAttr->specs->name }} /&yen; {{ $order->orderAttr->specs->price }}</em>
							<span>规格</span>
						</div>
					@endif
					<div class="flex endh">
						<em>&yen; {{ $order->price }}</em>
						<span>商品单价</span>
					</div>
					@if($order->orderAttr->packing == 2)
						<div class="flex endh"><em>&yen; 5</em><span>包装费</span></div>
					@endif
					<div class="flex endh"><em>&yen; {{ $order->orderAttr->postage }}</em><span>快递费用</span></div>
					<div class="flex endh"><em>&yen; {{ $order->orderAttr->postage_area }}</em><span>偏远地区费用</span></div>
				@endif
				<div class="flex endh"><em style="color:#f00;">&yen; {{ $order->pay_price }}</em><span>合计费用</span></div>
			</div>
			<div class="flex centerv rtitle"><i class="flexitem"></i><span class="flex center">支付记录</span></div>
			@if($order_pays->isNotEmpty())
				<div class="infotable">
					<table>
						<tbody>
							@foreach($order_pays as $order_pay)
								<tr>
									<td>
										@if($order_pay->mode == 1)
											微信支付
										@elseif($order_pay->mode ==2)
											支付宝支付
										@endif
									</td>
									<td>{{ $order_pay->number }}</td>
									<td>{{ $order_pay->created_at }}</td>
									<td align="right">
										@if($order_pay->status == 0)
											<span style="color:#f00;">支付取消</span>
										@elseif($order_pay->status == 1)
											<span style="color:#00FF01;">完成支付</span>
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			@else
				<div id="empty" class="flexv center"><i class="rd rd-empty icon"></i><p>该订单尚未支付~</p></div>
			@endif
			<div class="flex centerv rtitle"><i class="flexitem"></i><span class="flex center">收件人</span></div>
			<div class="infotable">
				<table>
					<tbody>
						<tr>
							<td width="80">收件人</td>
							<td>{{ $order->address->name }}</td>
							<td width="80">联系电话</td>
							<td>{{ $order->address->phone }}</td>
							<td width="80">收件地址</td>
							<td>{{ $order->address->province.$order->address->detail }}</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="flex tfoot">

			</div>
		</div>
	</div>
	<div class="widget-main">
		@if($order->status == 1 && $order->is_status == 0)
			<button type="button" class="btn btn-sm btn-danger" id="apply-refund" style="height: 40px;margin-bottom: 10px">&nbsp;申请退款&nbsp;</button>
		@endif
		@if($order->logistic)
			<form class="form-inline" action="{{ route('admin.edit_express', $order->logistic->id) }}" method="post">
				{{ csrf_field() }}
				<select class="form-control" name="express_name" style="width: 130px;height: 35px">
					@foreach(config('system.express_company') as $key => $company)
						<option value="{{ $key }}"@if($order->logistic->express_name == $key) selected @endif>{{ $company }}</option>
					@endforeach
				</select>
				<input type="hidden" name="id" value="{{ $order->logistic->id }}">
				<input type="text" name="express_number" value="{{ $order->logistic->express_number }}" style="height: 34px;width: 240px">
				<button class="btn btn-sm btn-success" type="submit" style="height: 35px;">&nbsp;修改&nbsp;</button>
			</form>
		@else
			<form class="form-inline" id="form" action="{{ route('admin.delivery', $order->id) }}">
				{{ csrf_field() }}
				<select class="form-control" name="express_name" style="width: 130px;height: 35px">
					@foreach(config('system.express_company') as $key => $company)
						<option value="{{ $key }}">{{ $company }}</option>
					@endforeach
				</select>
				<input type="text" name="express_number" value="" style="height: 34px;width: 240px" data-rule="*" data-errmsg="单号不能为空">
				<button class="btn btn-sm btn-success" type="button" id="submit" style="height: 35px;">&nbsp;发货&nbsp;</button>
			</form>
		@endif
	</div>

<script src="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"></script>
<script src="/js/checkform.js"></script>
<script src="/js/functions.js"></script>
<script src="/plugins/layer.js"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script>
    //询问框
    $('#apply-refund').click(function () {
	    var url = "{:url('Indent/applyRefund',['id'=>$list.id])}";
        layer.confirm('确定申请退款吗', {
            btn: ['取消','确定'] //按钮
        }, function(){
            $.get(url, function (ret) {
                layer.msg(ret.msg, {icon: 1});
                setTimeout(function () {
                    window.location.href = "{:url('indent/refund')}";
                },1000)
            });
        });
    });

    new checkForm({
        form : '#form',
        btn : '#submit',
        error : function (e,msg){
            showMsg(msg,0,2000);
        },
        complete : _.throttle(function (form){
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            $.post(url,datas,function(ret){
                console.log(ret);
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

