<?php

namespace App\Admin\Controllers;

use App\Models\Product;
use App\Models\Specification;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SpecificationsController extends Controller
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

            $content->header('规格列表');
            $content->description('规格列表');

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

            $content->header('修改规格');
            $content->description('修改规格');

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

            $content->header('新增规格');
            $content->description('新增规格');

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
        return Admin::grid(Specification::class, function (Grid $grid) {

            $grid->id('ID')->sortable();
            $grid->name('规格');
            $grid->price('价格(元)');
            $grid->product()->name('所属产品');
            $grid->created_at();
            $grid->updated_at();

            //按钮禁用
            $grid->disableExport();
            $grid->disableRowSelector();

            $grid->perPages([10, 20]);

            //数据过滤
            $grid->filter(function($filter){
                // 在这里添加字段过滤器
                $filter->like('name', '规格名');
                // 查询关联数据
                $filter->where(function ($query) {
                    $query->whereHas('product', function ($query) {
                        $query->where('name', 'like', "%{$this->input}%");
                    });
                }, '产品名');
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
        return Admin::form(Specification::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->text('name', '规格名');
            $form->number('price', '价格');
            $form->select('product_id', '所属商品')->options(Product::all()->pluck('name', 'id'));

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
