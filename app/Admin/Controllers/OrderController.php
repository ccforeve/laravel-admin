<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Excel\ExcelExpoter;
use App\Admin\Extensions\Tools\Logistic;
use App\Models\Address;
use App\Models\Order;
use App\Admin\Extensions\Tools\OrderAddress;
use App\Models\OrderAttr;
use App\Models\OrderRefund;
use App\Models\Specification;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    use ModelForm;

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
                elseif($status == 1) return '<color style="color:green">已支付</color>';
                elseif($status == 2) return '<color style="color:orange">已退款</color>';
            });
            $grid->is_status('订单状态')->display(function ($is_status) {
                if($is_status == 0) return '正常';
                elseif($is_status == 1) return '<color style="color:red">申请退款</color>';
                elseif($is_status == 2) return '<color style="color:orange">收货退款</color>';
                elseif($is_status == 3) return '<color style="color:orange">取消订单</color>';
            });
            $grid->comfirm('发货状态')->display(function ($comfirm) {
                if ( $comfirm == 0 ) return '<color style="color:red">未发货</color>';
                elseif ( $comfirm == 1 ) return '<color style="color:green">已发货</color>';
                elseif ( $comfirm == 2 ) return '<color style="color:orange">已退款</color>';
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
                // 添加操作
                $actions->append(new OrderAddress($actions->row->address['id'], $actions->row->address['name'], $actions->row->address['phone'],$actions->row->address['province'], $actions->row->address['detail']));
                if($actions->row->logistic_id) {
                    $actions->append(new Logistic());
                } else {
//                    $actions->append();
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
            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }

    public function editAddress(Request $request, Address $address)
    {
        $address->update($request->all());
        return response()->json(['state' => 0]);
    }
}
