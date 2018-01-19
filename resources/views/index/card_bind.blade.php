@include('index._header')

<body id="cardBind" class="flexv">
<div id="common-header" class="flex">
    <a href="javascript:history.back()" class=" cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>银行卡绑定</h1>
</div>
<div class="flexitemv scroll main">
    <form action="{{ route('index.get_code') }}" class="step1" method="post">
        {{ csrf_field() }}
        <h2 class="tips">请绑定持卡本人的银行卡</h2>
        <div class="content">
            <label class="flex">
                <span class="flex centerv">银行/账户</span>
                <select name="bank_name" class="flexitem" data-rule="*" data-errmsg="请选择银行或账户类型" style="-webkit-appearance: none;border: none;background: none;">
                    <option value="">请选择银行或账户类型</option>
                    <option value="支付宝" @if($user->bank_name == '支付宝') selected @endif>支付宝</option>
                    <option value="工商银行" @if($user->bank_name == '工商银行') selected @endif>工商银行</option>
                    <option value="农业银行" @if($user->bank_name == '农业银行') selected @endif>农业银行</option>
                    <option value="建设银行" @if($user->bank_name == '建设银行') selected @endif>建设银行</option>
                    <option value="交通银行" @if($user->bank_name == '交通银行') selected @endif>交通银行</option>
                    <option value="招商银行" @if($user->bank_name == '招商银行') selected @endif>招商银行</option>
                </select>
                <i class="flex center iconfont icon-go"></i>
            </label>
            <label class="flex">
                <span class="flex centerv">开户姓名</span>
                <input type="text" name="bank_username" placeholder="请填写您的开户姓名/真实姓名" class="flexitem" value="{{ $user->bank_username }}" data-rule="cname" data-errmsg="请填写您的开户姓名/真实姓名">
            </label>
            <label class="flex">
                <span class="flex centerv">卡号/账号</span>
                <input type="text" name="bank_number" placeholder="请填写您的银行卡/账户" class="flexitem" value="{{ $user->bank_number }}" data-rule="*" data-errmsg="请填写您的银行卡/账户">
            </label>
            <label class="flex">
                <span class="flex centerv">手机号</span>
                <input type="text" name="phone" placeholder="请填写接收验证码的手机号" class="flexitem" value="{{ $user->phone }}" data-rule="*" data-errmsg="请填写接收验证码的手机号">
            </label>
        </div>
        <div class="flex center sub">
            @if($user->bank_name)
                <a href="javascript:;" class="flex center submit next">修改</a>
            @else
                <a href="javascript:;" class="flex center submit next">下一步</a>
            @endif
        </div>
    </form>
    <form action="{{ route('index.check_code') }}" class="step2" method="post">
        {{csrf_field()}}

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

<script src="http://cdn.bootcss.com/zepto/1.2.0/zepto.min.js"></script>
<script src="/js/functions.js"></script>
<script src="/plugins/mobile/layer.js"></script>
<script src="/js/checkform.js"></script>
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
                if(ret.state == 0) {
                    showMsg(ret.error, 1);
                    setTimeout(function(){
                        step1.hide();
                        step2.show();
                        smsTimer(getCode, '重新获取', 60, 'disabled');
                    }, 1000);
                } else{
                    showMsg(ret.error);
                }
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
            var url = form.getAttribute('action');
            var datas = $(form).serializeArray();
            if(typeof(editor) == 'object' && editor.html()) datas[datas.length - 1] = {name:'content',value:editor.html()};
            $.post(url,datas,function(ret){
                if(ret.state == 0) {
                    showMsg(ret.error, 1);
                    if(ret.url) setTimeout(function (){window.location.href = ret.url;},1000);
                } else{
                    showMsg(ret.error);
                }
            },'json');
        }
    });

    getCode.click(function () {
        if (!$(this).hasClass('disabled')) {
            smsTimer(getCode, '重新获取', 60, 'disabled');
            //发送验证码
            var url = $('.step1').attr('action');
            $.post(url, {again:1,_token:"{{ csrf_token() }}"}, function (ret) {
                showMsg(ret.msg, 1);
                smsTimer(getCode, '重新获取', 60, 'disabled');
            })
        }
    });

</script>
</body>
</html>