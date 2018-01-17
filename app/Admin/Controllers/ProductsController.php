<?php

namespace App\Admin\Controllers;

use App\Models\Product;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ProductsController extends Controller
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

            $content->header('产品列表');
            $content->description('查看产品列表');

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

            $content->header('产品编辑');
            $content->description('更改产品信息');

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

            $content->header('产品添加');
            $content->description('添加产品信息');

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
        return Admin::grid(Product::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('产品名');
            $grid->original_price('原价');
            $grid->price('现价');
            $grid->type('类型')->display(function($type){
                return $type==1 ? '免费' : '套装';
            });
            $grid->shelves('上下架')->display(function($shelves){
                return $shelves==1 ? '上架' : '下架';
            });
            $grid->spec('规格')->display(function($spec){
                $name = '';
                foreach ($spec as $key => $value) {
                    if($key == count($spec)-1) $name .= $value['name'].'/'.$value['price'].'元';
                    else $name .= $value['name'].'/'.$value['price'] . '元，';
                }
                return $name;
            });
            $grid->created_at('添加时间');
            $grid->updated_at('更新时间');

            //隐藏按钮
            $grid->disableExport(); //禁用导出数据按钮
            $grid->disableRowSelector(); //禁用行选择checkbox
            $grid->disablePagination(); //禁用分页条

            //数据过滤
            $grid->filter(function ($filter) {
                $filter->like('name', '商品名称');
                $filter->equal('type', '类型')->radio([
                    ''   => '全部类型',
                    1    => '免费领取',
                    2    => '套装',
                ]);
                $filter->equal('shelves', '上下架')->radio([
                    ''   => '全部',
                    0    => '下架',
                    1    => '上架',
                ]);
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
        return Admin::form(Product::class, function (Form $form) {

            $form->display('id', 'ID');
            $form->text('name', '产品名');
            $form->number('original_price', '原价');
            $form->number('price', '现价');
            $form->number('stock', '库存');
            $form->number('buy_count', '销售量');
            $form->number('praise', '好评率');
            $form->number('sort', '排序')->help('越小越前');
            $form->radio('type', '类型')->options([1 => '免费', 2 => '套装']);
            $form->radio('shelves', '上下架')->options([0 => '下架', 1 => '上架']);
            $form->text('audio', '音频链接');
            $form->number('tl_free_num','限时免费数量');
            $form->number('tl_one_off','限时第一折扣');
            $form->number('tl_one_num','限时第一数量');
            $form->number('tl_two_off','限时第二折扣');
            $form->number('tl_two_num','限时第二数量');
            $form->datetime('tl_begin_time' ,'限时开始时间');
            $form->datetime('tl_end_time' ,'限时结束时间');
            $form->multipleImage('photo', '产品图/详情产品图/订单产品图')->removable()->help('按顺序上传即可');
            $form->ueditor('details', '产品详情');
            $form->display('created_at', '添加时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
