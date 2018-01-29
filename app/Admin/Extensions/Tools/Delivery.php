<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;

class Delivery
{
    protected $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    protected function script()
    {
        return <<<SCRIPT
        $('.delivery').click(function(){
        var url = $(this).attr('data-url'),
            token = $(this).attr('data-token'),
            content = '<form class="form-horizontal">'+
                        '<div class="box-body">'+
                            '<div class="fields-group">'+
                                '<div class="form-group  ">'+
                                    '<label for="status" class="col-sm-3 control-label">快递公司<\/label>'+
                                    '<div class="col-sm-8">'+
                                        '<select class="form-control select" style="width: 100%;">'+
                                            '<option value=""><\/option>'+
                                            '<option value="tiantian">天天快递<\/option>'+
                                            '<option value="youzhengguonei">邮政速递<\/option>'+
                                            '<option value="yunda">韵达快递<\/option>'+
                                            '<option value="shentong">申通快递<\/option>'+
                                            '<option value="huitongkuaidi">百世快递<\/option>'+
                                        '<\/select>'+
                                    '<\/div>'+
                                '<\/div>'+
                                '<div class="form-group ">'+
                                    '<label for="remark" class="col-sm-3 control-label">快递单号<\/label>'+
                                    '<div class="col-sm-8">'+
                                        '<div class="input-group">'+
                                            '<span class="input-group-addon"><i class="fa fa-pencil"><\/i><\/span>'+
                                            '<input type="text" name="order" class="form-control order" placeholder="输入 快递单号">'+
                                        '<\/div>'+
                                    '<\/div>'+
                                '<\/div>'+
                           '<\/div>'+
                        '<\/div>'+
                    '<\/form>';
    //询问框
    layer.confirm('收货地址', {
        btn: ['修改','取消'], //按钮
        skin: 'layui-layer-rim',
        area: ['620px', '360px'], //宽高
        content: content
    }, function(){
        var express = $(".select").val(),
            order = $("input[name=order]").val();
        $.post(url, {express_name:express, express_number:order, _token:token}, function(ret){
            if(ret.state == 0) {
                layer.msg('已添加物流信息', {icon: 1});
                setTimeout(function(){
                    window.location.reload();
                }, 1000)
            }
        });
    });

});
SCRIPT;
    }

    protected function render()
    {
        Admin::script($this->script());
        $url = route('admin.delivery', $this->id);
        $token = csrf_token();
        return "<a class='btn btn-xs btn-success fa delivery font' data-token='$token' data-url='$url'>发货</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}