<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Excel\ExcelExpoter;
use App\Admin\Extensions\Tools\UserGender;
use App\User;

use Encore\Admin\Auth\Permission;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Support\Facades\Request;

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
            $grid->paginate(15);
            $grid->perPages([10, 15]);
            $grid->id('ID')->sortable();
            $grid->name('姓名');
            $grid->email('邮箱');
            $grid->created_at('创建时间');
            $grid->updated_at('更新时间');

            $grid->tools(function ($tools) {
                $tools->append(new UserGender());
            });

            $grid->actions(function ($actions) {
                $actions->disableDelete();
//                $actions->disableEdit();
                $actions->append('<a href=""><i class="fa fa-eye"></i></a>');
            });

            if (in_array(Request::get('gender'), ['m', 'f'])) {
                $grid->model()->where('gender', Request::get('gender'));
            }

            if(Request::get('name') != '') {
                $grid->model()->where('name', Request::get('name'));
            }

            $arr = [0 => [
                'id' => 'id','name' => 'name', 'head'=>'head', 'gender'=>'gender', 'created_at'=>'created_at'
            ]];
            $grid->exporter(new ExcelExpoter('用户列表', (array)$arr));

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
            $form->text('name', '姓名')->rules('required');
            $form->text('email', '11')->rules('required');
//            $form->password('password', '密码');
            $form->display('created_at', '新增时间');
            $form->ueditor('details', '内容');
            $form->disableReset();

//            $form->setAction('admin/users');//设置表单提交的action

            $form->image('head');

            $form->saving(function (Form $form) {
                if ($form->password && $form->model()->password != $form->password) {
                    $form->password = bcrypt($form->password);
                }
            });
        });
    }
}
