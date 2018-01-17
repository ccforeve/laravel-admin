@include('index._header')

<body id="cardBind" class="flexv">
<div id="common-header" class="flex">
    <a href="javascript:history.back()" class=" cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>银行卡绑定</h1>
</div>
<div class="flexitemv scroll main">
    <form action="{:url('User/cardBind')}" class="step1" method="post">
        <h2 class="tips">请绑定持卡本人的银行卡</h2>
        <div class="content">
            <label class="flex">
                <span class="flex centerv">银行/账户</span>
                <select name="bn" class="flexitem" data-rule="*" data-errmsg="请选择银行或账户类型" style="-webkit-appearance: none;border: none;background: none;">
                    <option value="">请选择银行或账户类型</option>
                    <option value="1" {eq name="bank.bank_name" value="1"}selected{/eq}>支付宝</option>
                    <option value="2" {eq name="bank.bank_name" value="2"}selected{/eq}>工商银行</option>
                    <option value="3" {eq name="bank.bank_name" value="3"}selected{/eq}>农业银行</option>
                    <option value="4" {eq name="bank.bank_name" value="4"}selected{/eq}>建设银行</option>
                    <option value="5" {eq name="bank.bank_name" value="5"}selected{/eq}>交通银行</option>
                    <option value="6" {eq name="bank.bank_name" value="6"}selected{/eq}>招商银行</option>
                </select>
                <i class="flex center iconfont icon-go"></i>
            </label>
            <label class="flex">
                <span class="flex centerv">开户姓名</span>
                <input type="text" name="bu" placeholder="请填写您的开户姓名/真实姓名" class="flexitem" value="{$bank.bank_username}" data-rule="cname" data-errmsg="请填写您的开户姓名/真实姓名">
            </label>
            <label class="flex">
                <span class="flex centerv">卡号/账号</span>
                <input type="text" name="bsn" placeholder="请填写您的银行卡/账户" class="flexitem" value="{$bank.bank_number}" data-rule="*" data-errmsg="请填写您的银行卡/账户">
            </label>
        </div>
        <div class="flex center sub">
            {eq name='bank.bank_name' value=''}
                <a href="javascript:;" class="flex center submit next">下一步</a>
            {else /}
                <a href="javascript:;" class="flex center submit next">修改</a>
            {/eq}
        </div>
    </form>
    <form action="{:url('validateCode')}" class="step2" method="post">
        <h2 class="tips">系统已给本账号用户手机发送了短信验证，请输入验证码之后提交。</h2>
        <div class="content">
            <label class="flex">
                <span class="flex centerv">卡号/账户</span>
                <input type="number" class="flexitem" name="code" placeholder="短信验证码" data-rule="/^\d{6}$/" data-errmsg="短信验证码错误">
                <a href="javascript:;" class="flex center disabled getCode">重新获取</a>
            </label>
        </div>
        <div class="sub">
            <a href="javascript:;" class="flex center submit complete">提交绑定</a>
        </div>
    </form>
</div>

{js href="http://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"}
{js href="__STATIC__/js/functions.js"}
{js href="__INDEX__/plugins/mobile/layer.js"}
{js href="__INDEX__/js/checkform.js"}
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
<script>
    $('a').last().hide();

    var step2 = $('.step2');
    var step1 = $('.step1');
    var getCode = $('.getCode');

    new checkForm({
        form: '.step1',
        btn: '.next',
        error: function (obj, msg) {
            layer.open({
                content: msg,
                skin: 'msg',
                anim: 'scale',
                time: 2
            });
        },
        complete: function (form) {
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            if(typeof(editor) == 'object' && editor.html()) datas[datas.length - 1] = {name:'content',value:editor.html()};
            $.post(url,datas,function(ret){
                showMsg(ret.msg,ret.code);
                step1.hide();
                step2.show();
                smsTimer(getCode, '重新获取', 60, 'disabled');
            },'json');
        }
    });

    new checkForm({
        form: '.step2',
        btn: '.complete',
        error: function (obj, msg) {
            layer.open({
                content: msg,
                skin: 'msg',
                anim: 'scale',
                time: 2
            });
        },
        complete: function (form) {
            step1.hide();
            step2.show();
            smsTimer(getCode, '重新获取', 60, 'disabled');
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            if(typeof(editor) == 'object' && editor.html()) datas[datas.length - 1] = {name:'content',value:editor.html()};
            $.post(url,datas,function(ret){
                showMsg(ret.msg,ret.code);
                if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
            },'json');
        }
    });

    getCode.click(function () {
        if (!$(this).hasClass('disabled')) {
            smsTimer(getCode, '重新获取', 60, 'disabled');
            //发送验证码
        }
    });

</script>
</body>
</html>