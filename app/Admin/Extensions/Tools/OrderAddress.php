<?php

namespace App\Admin\Extensions\Tools;

use Encore\Admin\Admin;

class OrderAddress
{
    protected $id, $name, $phone, $provision, $details;

    public function __construct($id, $name, $phone, $provision, $details)
    {
        $this->id = $id;
        $this->name= $name;
        $this->phone = $phone;
        $this->provision = $provision;
        $this->details = $details;
    }

    protected function script()
    {

        return <<<SCRIPT
$('.fa-check').click(function(){
    var url = $(this).attr('data-url'),
        name = $(this).attr('data-name'),
        phone = $(this).attr('data-phone'),
        province = $(this).attr('data-province'),
        details = $(this).attr('data-details'),
        token = $(this).attr('data-token');
    var content =   '<form class="form-horizontal">'+
                        '<div class="box-body">'+
                            '<div class="fields-group">'+
                                '<div class="form-group ">'+
                                    '<label for="remark" class="col-sm-3 control-label">收件人姓名</label>' +
                                    '<div class="col-sm-8">'+
                                        '<div class="input-group">'+
                                            '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'+
                                            '<input type="text" name="name" value="' + name + '" class="form-control remark" placeholder="输入 收件人姓名">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-group ">'+
                                    '<label for="remark" class="col-sm-3 control-label">收件人手机</label>' +
                                    '<div class="col-sm-8">'+
                                        '<div class="input-group">'+
                                            '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'+
                                            '<input type="text" name="phone" value="' + phone + '" class="form-control remark" placeholder="输入 收件人手机">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-group ">'+
                                    '<label for="remark" class="col-sm-3 control-label">省市</label>' +
                                    '<div class="col-sm-8">'+
                                        '<div class="input-group">'+
                                            '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'+
                                            '<input type="text" name="province" value="' + province + '" class="form-control remark" placeholder="输入 省市">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                '<div class="form-group ">'+
                                    '<label for="remark" class="col-sm-3 control-label">详细地址</label>' +
                                    '<div class="col-sm-8">'+
                                        '<div class="input-group">'+
                                            '<span class="input-group-addon"><i class="fa fa-pencil"></i></span>'+
                                            '<input type="text" name="details" value="' + details + '" class="form-control remark" placeholder="输入 详细地址">'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                            '</div>'+
                        '</div>'+
                    '</form>';
    //询问框
    layer.confirm('收货地址', {
        btn: ['修改','取消'], //按钮
        skin: 'layui-layer-rim',
        area: ['620px', '360px'], //宽高
        content: content
    }, function(){
        var name = $("input[name=name]").val(),
            phone = $("input[name=phone]").val(),
            province = $("input[name=province]").val(),
            details = $("input[name=details]").val();
        $.post(url, {name:name, phone:phone, province:province, detail:details, _token:token}, function(ret){
            if(ret.state == 0) {
                layer.msg('地址已修改', {icon: 1});
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
        $url = route('admin.edit_address', $this->id);
        $token = csrf_token();
        return "<a class='btn btn-xs btn-success fa' data-token='$token' data-url='$url' data-name='$this->name' data-phone='$this->phone' data-province='$this->provision' data-details='$this->details'>查看地址</a>";
    }

    public function __toString()
    {
        return $this->render();
    }
}