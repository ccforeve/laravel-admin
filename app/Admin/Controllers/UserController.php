<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\Tools\UserGender;
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

            $content->header('用户列表');
            $content->description('用户列表');
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

            $content->header('查看用户信息');
            $content->description('description');

            $content->body($this->form()->edit($id));
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
            if(\Request::get('type') == 1) {
                $grid->model()->where('type', \Request::get('type'));
                //扩展行操作
                $grid->actions(function ($actions) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                    //判断是否能成为经销商或成为哪级的经销商
                    if($actions->row->dealer) {
                        if(!$actions->row->dealer['dealer_id']) {
                            $actions->prepend('<a href="' . route('become_dealer', $actions->getKey()) . '" class="btn btn-sm btn-primary">成为二级经销商</a>');
                        }
                    } else {
                        $actions->prepend('<a href="'.route('become_dealer', $actions->getKey()).'" class="btn btn-sm btn-primary">成为经销商</a>');
                    }
                    $actions->append('<a href="'.route('users.show', $actions->getKey()).'" class="btn btn-sm btn-primary">查看</a>');
                });
            } elseif(\Request::get('type') == 2) {
                $grid->model()->where('type', \Request::get('type'));
                //扩展行操作
                $grid->actions(function ($actions) {
                    $actions->disableDelete();
                    $actions->disableEdit();
                    $actions->append('<a href="' . route('users.show', $actions->getKey()) . '" class="btn btn-sm btn-primary">查看</a>');
                });
            }

            //数据过滤
            $grid->filter(function($filter){
                // 在这里添加字段过滤器
                $filter->like('nickname', '昵称');
            });


            $grid->id('ID')->sortable();
            $grid->nickname('昵称');
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
            $grid->disableExport();
            $grid->disableRowSelector();
            $grid->disableCreation();
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
            $form->image('head', '头像');
            $form->radio('type', '类型')->options([1 => '普通用户', 2 => '经销商']);
            $form->display('created_at', '创建时间');
            $form->display('updated_at', '更新时间');
            $form->disableSubmit();
            $form->disableReset();

            $form->tools(function (Form\Tools $tools) {
                // 去掉跳转列表按钮
                $tools->disableListButton();
            });
        });
    }

    /**
     * 成为经销商
     * @param User $user
     * @return mixed
     */
    public function becomeDealer(User $user)
    {
        $user->where('id', $user->id)->update(['type' => 2]);
        //该用户推广的下级用户更改关系
        $user->where('p_id', $user->id)->update(['p_id' => 0, 'dealer_id' => $user->id]);

        return redirect('/admin/users?type=1');
    }
}
