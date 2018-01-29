<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Excel\ExcelExpoter;
use App\Admin\Extensions\Tools\Delivery;
use App\Classes\Kd\Kuaidi;
use App\Http\TraitFunction\Notice;
use App\Models\Address;
use App\Models\Logistic;
use App\Models\Order;
use App\Admin\Extensions\Tools\OrderAddress;
use App\Models\OrderAttr;
use App\Models\OrderPay;
use Carbon\Carbon;
use EasyWeChat\Factory;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class OrderController extends Controller
{
    use ModelForm, Notice;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('总订单列表');
            $content->description('总订单列表');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(Order::class, function (Grid $grid) {
            $grid->model()->where('complete', 1)->orderBy('created_at', 'desc');

            $grid->id('ID')->sortable();
            $grid->product()->id('产品ID');
            $grid->product()->name('产品名');
            $grid->address()->name('收货人姓名');
            $grid->address()->phone('收货人手机');
            $grid->product_type('类型')->display(function ($type) {
                if($type == 1) return '<color style="color: #00a0e9">免费</color>';
                elseif($type == 2) return '<color style="color: green">套装</color>';
                elseif($type == 3) return '<color style="color: orange">体验</color>';
            });
            $grid->order_attr_id('规格')->display(function($attr){
                if($attr) {
                    $attrs = OrderAttr::with('specs')->where('id', $attr)->first();
                    if($attrs->specs) {
                        return $attrs->specs->name;
                    }
                }
                return '';
            });
            $grid->orderAttr()->packing('包装')->display(function ($pack){
                if($pack) {
                    return $pack == 1 ? '不包装' : '包装';
                }
                return '';
            });
            $grid->activity('活动')->display(function ($activity) {
                if($activity) {
                    return '是';
                }
                return '';
            });
            $grid->pay_price('支付金额/元');
            $grid->status('支付状态')->display(function($status){
                if($status == 0) return '<color style="color:red">未支付</color>';
                elseif($status == 1) return '<color style="color:green;font-weight: bold;">已支付</color>';
                elseif($status == 2) return '<color style="color:orange">已退款</color>';
            });
            $grid->is_status('订单状态')->display(function ($is_status) {
                if($is_status == 0) return '正常';
                elseif($is_status == 1) return '<color style="color:red">申请退款</color>';
                elseif($is_status == 2) return '<color style="color:orange">收货退款</color>';
                elseif($is_status == 3) return '<color style="color:orange">取消订单</color>';
            });
            $grid->confirm('发货状态')->display(function ($confirm) {
                if ( $confirm == 0 ) return '<color style="color:red">未发货</color>';
                elseif ( $confirm == 1 ) return '<color style="color:green;font-weight: bold;">已发货</color>';
                elseif ( $confirm == 2 ) return '<color style="color:orange">已退款</color>';
            });
//            $grid->order_refund_id('退款状态')->display(function($refund){
//                if($refund) {
//                    $refund = OrderRefund::find($refund);
//                    if($refund->statue) {
//                        return '退款完成';
//                    }
//                    return '申请中';
//                }
//                return '';
//            });
            $grid->extension()->nickname('推广人');
            $grid->dealer()->nickname('经销商');
            $grid->remark('备注')->editable();
            $grid->updated_at('最后更新时间');

            //分页
            $grid->paginate(15);
            $grid->perPages([10, 15]);

            //数据过滤
            $grid->filter(function($filter) {
                $filter->where(function ($query) {
                    // 在这里添加字段过滤器
                    $query->whereHas('address', function ($query) {
                        $query->where('name', 'like', "%{$this->input}%")->orWhere('phone', 'like', "%{$this->input}%");
                    });
                }, '收件人或手机号');

                $filter->equal('status', '支付状态')->radio([
                    ''   => '全部',
                    0    => '未支付',
                    1    => '已支付',
                    2    => '已退款',
                ]);

                $filter->equal('product_type', '商品类型')->radio([
                    ''   => '全部',
                    1    => '免费领取',
                    2    => '套装',
                    3    => '体验商品',
                ]);

                $filter->equal('confirm', '商品类型')->radio([
                    ''   => '全部',
                    0    => '未发货',
                    1    => '已发货',
                    2    => '已收货',
                ]);
            });

            $grid->exporter(new ExcelExpoter('未发货订单'));

            //禁用添加按钮
            $grid->disableCreation();
            //扩展按钮
            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();

                $url = route('admin.order_detail', $actions->row->id);
                $actions->append("<a href={$url} class='btn btn-xs btn-success fa font'>详情</a>");
                // 添加操作
                if($actions->row->status <> 0) {
                    //查看地址
                    $actions->append(new OrderAddress($actions->row->address[ 'id' ], $actions->row->address[ 'name' ], $actions->row->address[ 'phone' ], $actions->row->address[ 'province' ], $actions->row->address[ 'detail' ]));
                }
                if($actions->row->confirm == 0 && $actions->row->status == 1) {
                    //发货
                    $actions->append(new Delivery($actions->row->id));
                } elseif($actions->row->confirm <> 0) {
                    //查看物流
                    $actions->append(new \App\Admin\Extensions\Tools\Logistic($actions->row->logistic_id));
                }
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Order::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('remark', '备注');
        });
    }

    /**
     * 订单详情页
     * @param Order $order
     * @return Content
     */
    public function detail( $order )
    {
        return Admin::content(function (Content $content) use($order) {
            $content->header('订单详情');

            $order = Order::with('product','address','orderAttr.specs','logistic')->where('id', $order)->first();

            $order_pays = OrderPay::where('order_id', $order->id)->get();

            $content->body(view('admin.order_detail', compact('order', 'order_pays')));
        });
    }

    /**
     * 修改收货地址
     * @param Request $request
     * @param Address $address
     * @return \Illuminate\Http\JsonResponse
     */
    public function editAddress(Request $request, Address $address)
    {
        $address->update($request->all());

        return response()->json(['state' => 0]);
    }

    /**
     * 订单列表填写快递信息
     * @param Request $request
     * @param $order
     * @return \Illuminate\Http\JsonResponse
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     */
    public function delivery( Request $request, $order)
    {
        $add_logistic = Logistic::create($request->all());
        Order::where('id', $order)->update(['logistic_id'=>$add_logistic->id, 'confirm' => 1, 'receipt_at'=>Carbon::now()->addDays(7)]);

        $order = Order::with('user', 'product', 'logistic', 'address')->where('id', $order)->first();

        //推送消息
        if($order->user->subscribe) {
            $app = Factory::officialAccount(config('wechat'));
            $app->template_message->send([
                'touser'      => "{$order->user->openid}",
                'template_id' => '3abfbjwBmy2Y7THJvDSUBddwXYCrskrcSc6_hasOALA',
                'url'         => config('app.url'),
                'data'        => [
                    'first' => '您购买的商品已发货，查看物流信息请点击详情',
                    'key1' => $order->product->name,
                    'key2' => config("system.express_company.{$order->logistic->express_name}"),
                    'key3' => $order->logistic->express_number,
                    'key4' => $order->address->province . $order->address->detail,
                    'key5' => '如有任何问题，请您联系在线客服。感谢您对我们的信赖与支持，期待您下次光临！'
                ],
            ]);
            //发送短信通知
            $appid = 1400037875;
            $appkey = "f98b59234537f5bd3ab6850e1e2c1e9d";
            self::sms($appid, $appkey, $order->address->phone, 72565, [$order->product->name], '订单添加物流通知');
        }

        return response()->json(['state' => 0]);
    }

    /**
     * 修改快递信息
     * @param Request $request
     * @param Logistic $logistic
     * @return \Illuminate\Http\RedirectResponse
     */
    public function editExpress(Request $request, Logistic $logistic )
    {
        $logistic->update($request->all());

        return redirect()->back();
    }

    public function seeExpress( Logistic $logistic )
    {
        $kd = new Kuaidi();
        $res = json_decode($kd->getTransport($logistic->express_name, $logistic->express_number),true);
        if($res['status']==1){
            $datas = $res['data'];

            return view('admin.order_truck', compact('datas'));
        }
    }
}
