/*** Created by 胡成铭 on 2017/10/25.*/
//    底部tab
$(".tabbars a").click(function(){
    $(this).addClass("current").siblings().removeClass("current");
    if($(".tabbars a").hasClass("current")){
        if($(this).find("i").hasClass("xg-classify")){
            $(this).find("i").addClass("xg-classify-l").removeClass("xg-classify");
        }else if($(this).find("i").hasClass("xg-service")){
            $(this).find("i").addClass("xg-service-l").removeClass("xg-service");
        }else if($(this).find("i").hasClass("xg-mine")){
            $(this).find("i").addClass("xg-mine-l").removeClass("xg-mine");
        }
    }
    var num1 = $(this).siblings().find('i');
    $(num1).each(function () {
        var sum = $($(this)[0]);
        if(sum.hasClass("xg-classify-l")){
            sum.removeClass("xg-classify-l").addClass("xg-classify");
        }
        if(sum.hasClass("xg-service-l")){
            sum.removeClass("xg-service-l").addClass("xg-service");
        }
        if(sum.hasClass("xg-mine-l")){
            sum.removeClass("xg-mine-l").addClass("xg-mine");
        }
    })
});
// 客服
$(".servicebtn").click(function(){
    $("#alert").addClass("show").css("visibility","visible");
});
// 取消
$("#alert a.cancel").click(function () {
    $("#alert").removeClass("show").css("visibility","hidden");
});

/**
 * 短信验证码定时器
 * @param obj 发送按钮
 * @param html 设置文字
 * @param interval 间隔时间
 * @param cls 移除元素设置的class
 */
function smsTimer(obj,html,interval,cls){
    obj.val(interval + 's后' + html);
    interval --;
    var time = setInterval(function (){
        if(interval == 0){
            obj.attr('style','').removeClass(cls).val(html);
            clearInterval(time);
        }else{
            obj.val((interval --) + 's后' + html);
        }
    },1000);
}
