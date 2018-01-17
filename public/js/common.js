
/*客服弹出层效果*/
var kefu = document.querySelector('#kefu');

if (kefu) {
    kefu.addEventListener('click', function () {
        layer.open({
            content: '<div class="flexv center ins"><img src="/kefu.jpg" alt=""><p>长按二维码添加关注</p></div>'
            , btn: ['取消']
            ,anim: 'up'
            , skin: 'footer'
            , yes: function (index) {
                layer.close(index);
            }
        });
    });
}
/*客服弹出层效果结束*/

/*领取须知出层效果*/
var xuzhi = document.querySelector('#xuzhi');
if (xuzhi) {
    xuzhi.addEventListener('click', function () {
        var notice = $('#notice').val().replace(/[\r\n]/g,"");
        layer.open({
            content: "<div style='text-align: left'>" + notice + "</div>"
            , btn: ['取消']
            , anim: 'up'
            , skin: 'footer'
            , yes: function (index) {
                layer.close(index);
            }
        });
    });
}
/*领取须知出层效果结束*/

/*订单付款页*/
var payObj= document.querySelector('#order-pay');
var integral = payObj ? payObj.querySelector('#integral') : null;

if (payObj) {

    /*表单验证*/
    new checkForm({
        form: '#pay',
        btn: '.btn',
        error: function (obj, msg) {
            layer.open({
                content: msg,
                skin: 'msg',
                anim: 'scale',
                time: 2
            });
        },
        complete: function () {

        }
    });
    /*表单验证结束*/

}
/*订单付款页结束*/

/*商品详情页*/
var goodsObj = document.querySelector('#goods');

if(goodsObj) {
    /*表单验证*/
    // new checkForm({
    //     form: '#form',
    //     btn: '.sub',
    //     error: function (obj, msg) {
    //         layer.open({
    //             content: msg,
    //             skin: 'msg',
    //             anim: 'scale',
    //             time: 2
    //         });
    //     },
    //     complete: function () {
    //
    //     }
    // });
    /*表单验证结束*/

    /*弹出层效果*/

    //包装选择
    if(goodsObj.querySelector('.pack-btn')) {
        goodsObj.querySelector('.pack-btn').addEventListener('click', function () {
            var oSpan = this.querySelector('span');
            var oInput = this.querySelector('input');

            layer.open(({
                content: '<div class="sl"><p class="sl-item" value="1" price="5">需要包装</p><p class="sl-item" value="2" price="0">不需要包装</p></div>'
                , skin: 'footer'
                , anim: 'up'
                , btn: ['取消']
                , success: function (el) {
                    el.addEventListener('click', function (e) {
                        var obj = e.target;
                        var postagePrice = Number(document.querySelector('.postage').getAttribute('price'));
                        var oPrice = document.querySelector('.price');
                        var iPrice = document.querySelector('#i_price');
                        var specPrice = document.querySelector('.spec-price') ? document.querySelector('.spec-price').getAttribute('price') : 0;
                        if (obj.className == 'sl-item') {
                            oSpan.innerHTML = obj.innerText;
                            oSpan.setAttribute('price', obj.getAttribute('price'));
                            oInput.value = obj.getAttribute('value');
                            oPrice.innerText = '总计：¥ ' + (postagePrice + Number(obj.getAttribute('price')) + Number(specPrice)).toFixed(2);
                            iPrice.value = (postagePrice + Number(obj.getAttribute('price')) + Number(specPrice));
                            layer.closeAll();
                        }
                    });
                }
            }));
        });
    }
    //包装选择结束

    /*弹出层效果结束*/
}

/*商品详情页结束*/

