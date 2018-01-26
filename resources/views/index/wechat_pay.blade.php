<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>支付页面</title>
</head>
<body>

</body>
<script type="text/javascript" src="//res.wx.qq.com/open/js/jweixin-1.2.0.js"></script>
<script>
    function onBridgeReady() {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest', {!! $ret !!},
            function (res) {
                if (res.err_msg == "get_brand_wcpay_request:ok") { // 支付成功
                    window.location.href = "{{ route('index.order_result', ['state'=>'ok', 'id'=>$order_id]) }}";
                } else if(res.err_msg == "get_brand_wcpay_request:cancel") { // 支付过程中用户取消
                    window.location.href = "{{ route('index.order_result', ['state'=>'cancel', 'id'=>$order_id]) }}";
                } else if(res.err_msg == "get_brand_wcpay_request:fail") { // 支付失败
                    window.location.href = "{{ route('index.order_result', ['state'=>'fail', 'id'=>$order_id]) }}";
                }
            }
        );
    }

    if (typeof WeixinJSBridge == "undefined"){
        if( document.addEventListener ){
            document.addEventListener('WeixinJSBridgeReady', onBridgeReady, false);
        }else if (document.attachEvent){
            document.attachEvent('WeixinJSBridgeReady', onBridgeReady);
            document.attachEvent('onWeixinJSBridgeReady', onBridgeReady);
        }
    }else{
        onBridgeReady();
    }
</script>
</html>