<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no"/>
    <meta name="format-detection" content="telephone=no,email=no,date=no,address=no">
    <title>修改收货地址</title>
    <link rel="stylesheet" href="https://cdn.bootcss.com/Swiper/3.4.2/css/swiper.min.css">
    <link rel="stylesheet" href="/css/font.css">
    <link rel="stylesheet" href="/css/index.css">
    <link rel="stylesheet" href="/css/reset.css">
</head>
<body>
<div id="newsite" class="flexv wrap">
    <!--头部-->
    <div class="flex center head">
        <a href="{{ route('address.index') }}" class="flex center xg xg-left"></a>
        <h1 class="flexitem center">修改收货地址</h1>
    </div>
    <!--地址信息-->
    <form action="{{ route('address.update', $address->id) }}" class="flexv padlft" id="form">
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        <label class="around input">
            <span class="flex centerv">收货人</span>
            <input type="text" name="name" class="flexitem" value="{{ $address->name }}" placeholder="收货人姓名" data-rule="*" data-errmsg="收货人格式不正确">
            <i class="flex center xg xg-right"></i>
        </label>
        <label class="around input">
            <span class="flex centerv">手机号码</span>
            <input type="text" name="phone" class="flexitem" value="{{ $address->phone }}" placeholder="配送员联系您的电话" maxlength="11" data-rule="*" data-errmsg="手机号码格式不正确">
            <i class="flex center xg xg-right"></i>
        </label>
        <label class="around input">
            <span class="flex centerv">所在城市</span>
            <input id="sel_city" name="province" readonly="readonly" value="{{ $address->province }}" class="flexitem" placeholder="选择您所在的城市" data-rule="*" data-errmsg="请填写所在城市">
            <i class="flex center xg xg-right"></i>
        </label>
        <label class="around input site">
            <span class="flex centerv">收货地址</span>
            <textarea class="flexitem" name="detail" style="padding: 0.5rem 0;" data-rule="*" data-errmsg="请填写收货地址">{{ $address->detail }}</textarea>
            <em class="flex center hint"><i class="xg xg-location"></i>小区/写字楼</em>
            <i class="flex center xg xg-right"></i>
        </label>
    </form>
    <div class="btnbox">
        <a href="javascript:;" class="flex center marbotm save" id="submit">保存收货信息</a>
        <a href="javascript:;" class="flex center delbtn" data-url="{{ route('address.destroy', $address->id) }}">删除收货信息</a>
    </div>
</div>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
<script src="https://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="/js/footer.js"></script>
<script src="/js/city.js"></script>
<script src="/js/picker.min.js"></script>
<script src="/js/city-js.js"></script>
<script src="/js/checkform.js"></script>
<script src="/js/functions.js"></script>
<script src="https://cdn.bootcss.com/lodash.js/4.17.4/lodash.min.js"></script>
<script type="text/javascript">
    $('a').last().hide();

    var textarea = $('.site textarea');

    if (textarea.val() != '') {
        $('.site .hint').hide();
    }
    //    input 提示图标
    textarea.on('input propertychange',function(){
        var val = $(this).val();
        if(val.length > 0) {
            $('.site .hint').hide();
        }else{
            $('.site .hint').show();
        }
    });

    //修改
    new checkForm({
        form : '#form',
        btn : '#submit',
        error : function (e,msg){
            showMsg(msg,0,2000);
        },
        complete : _.throttle(function (form){
            showProgress('请稍后...');
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            $.post(url,datas,function(ret){
                hideProgress();
                if(ret.state == 0) showMsg(ret.error, 1);
                else showMsg(ret.error);
                if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
            },'json');
        }, 5000, { 'trailing': false })
    });

    // 删除提示框
    $("a.delbtn").click(function () {
        var url = $(this).attr('data-url');
        var text = $(this).text(),delalert = $('<div id="delalert"><div class="mask"></div><div class="flexv content"><div class="flex center title">温馨提示</div><div class="flex center text"></div><div class="between"><button class="cancel">取消</button><button class="del">删除</button></div></div></div>');
        delalert.find(".text").text("确认"+text);
        $(".wrap").append(delalert);
        var offset = {'height':$("#delalert .content").height(),'width':$("#delalert .content").width()};
        $("#delalert .content").css({'margin':parseInt(-offset.height/2)+'px 0 0'+parseInt(-offset.width/2)+"px",'visibility':'visible'});
        $(".between .del").click(function () {
            $.post(url, {_token:"{{csrf_token()}}",_method:"DELETE"}, function (ret) {
                if(ret.state == 0) {
                    window.location.href = ret.url;
                }
            })
        });
        $(".between .cancel").click(function () {
           $("#delalert").remove()
        })
    });
</script>
</html>