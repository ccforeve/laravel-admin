<?php

namespace App\Admin\Controllers;

use App\Models\User;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UserController extends Controller
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

            $content->header('header');
            $content->description('description');

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
        return Admin::grid(User::class, function (Grid $grid) {
            $grid->id('ID')->sortable();
            $grid->nickname('姓名');
            $grid->head('头像')->display(function($head) {
                return "<img src='$head' width='80px' height='80px'>";
            });
            $grid->type('类型')->display(function($type){
                return $type==1 ? '普通用户' : '经销商';
            });
            $grid->extension()->nickname('推广人');
            $grid->dealer()->nickname('经销商');
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            //分页
            $grid->paginate(15);
            $grid->perPages([10, 15]);

            //禁用按钮
            $grid->actions(function ($actions) {
                $actions->disableDelete();
            });
            $grid->disableExport();
            $grid->disableRowSelector();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {
            $form->display('id', 'ID');
            $form->display('openid', '微信openid');
            $form->display('nickname', '昵称');
            $form->display('head', '头像');
            $form->radio('type', '类型')->options([1 => '普通用户', 2 => '经销商']);
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
        });
    }
}
