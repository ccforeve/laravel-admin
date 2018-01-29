<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;

class Logistic
{
    protected $id, $logistic_id;

    public function __construct($logistic_id)
    {
        $this->logistic_id = $logistic_id;
    }

    protected function script()
    {
        return <<<SCRIPT
            $('.fa-truck').click(function () {
                var url = $(this).attr('data-url');
                //iframe窗
                layer.open({
                    type: 2,
                    title: false,
                    shade: [0],
                    area: ['540px', '515px'],
                    offset: 'ct', //居中弹出
                    anim: 2,
                    content: [url, 'yes'] //iframe的url，no代表不显示滚动条
                });
            });
SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());
        $url = route('admin.see_express', $this->logistic_id);

        return "<a class='btn btn-xs btn-success fa fa-truck font' data-url='$url'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}