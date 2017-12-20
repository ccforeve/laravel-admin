<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/20 0020
 * Time: 上午 9:59
 */
namespace App\Admin\Extensions\Form;

use Encore\Admin\Form\Field;

class UEditor extends Field
{
    public static $js = [
        '/vendor/ueditor/ueditor.config.js',
        '/vendor/ueditor/ueditor.all.min.js',
        '/vendor/ueditor/lang/zh-cn/zh-cn.js'
    ];

    protected $view = 'admin.ueditor';

    public function render()
    {
        $this->script = "UE.getEditor('UEditor');";

        return parent::render();
    }
}