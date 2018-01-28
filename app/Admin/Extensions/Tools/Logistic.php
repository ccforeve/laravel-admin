<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;

class Logistic
{
    protected $id;

    public function __construct()
    {

    }

    protected function script()
    {
        return <<<SCRIPT
        
SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());

        return "<a class='btn btn-xs btn-success fa fa-truck font'></a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}