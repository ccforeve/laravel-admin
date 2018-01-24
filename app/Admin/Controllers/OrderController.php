<?php

namespace App\Admin\Controllers;

use App\Models\Order;

use App\Models\OrderAttr;
use App\Models\Specification;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

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
            $grid->model()->where('complete', 1);

            $grid->id('ID')->sortable();
            $grid->product()->id('产品ID');
            $grid->product()->name('产品名');
            $grid->address()->name('收货人姓名');
            $grid->address()->phone('收货人手机');
            $grid->product_type('类型')->display(function ($type) {
                if($type == 1) return '免费';
                elseif($type == 2) return '套装';
                elseif($type == 3) return '体验';
            });
            $grid->order_attr_id('规格')->display(function($attr){
                if($attr) {
                    $attrs = OrderAttr::with('specs')->where('id', $attr)->first();

                    return $attrs->specs->name;
                }
                return '';
            });
            $grid->orderAttr()->packing('包装')->display(function ($pack){
                if($pack) {
                    return $pack == 1 ? '不包装' : '包装';
                }
                return '';
            });
            $grid->status('状态')->display(function($status){
                if($status == 0) return '<color style="color:red">未支付</color>';
                elseif($status == 1) return '<color style="color:green">已支付</color>';
                elseif($status == 2) return '<color style="color:orange">已退款</color>';
            });
            $grid->pay_price('支付金额/元');
            $grid->created_at('下单时间');
            $grid->updated_at('最后更新时间');
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

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
