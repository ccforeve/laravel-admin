//倒计时
//倒计时
(function ($) {
    var defaults = {
        startTimeStr: "2017/08/03 00:00:00",
        endTimeStr: "2017/09/17 23:59:59",
        daySelector: ".day",
        hourSelector: ".hour",
        minSelector: ".min",
        secSelector: ".sec"
    };
    var day, hour, min, sec;

    $.fn.extend({
        countDown: function (options) {  //生成倒计时字符串
            var opts = $.extend({}, defaults, options);
            this.each(function () {
                var $this = $(this);
                var startTime = new Date(opts.startTimeStr).getTime(); //开始时间
                var endTime = new Date(opts.endTimeStr).getTime();  //结束时间

                console.log(startTime, endTime);

                var intervalDate = setInterval(function () {
                    var startTime = new Date().getTime();
                    if (endTime > startTime) {
                        //显示倒计时
                        var t = endTime - startTime;
                        day = Math.floor(t / 1000 / 60 / 60 / 24);
                        hour = Math.floor(t / 1000 / 60 / 60 % 24);
                        min = Math.floor(t / 1000 / 60 % 60);
                        sec = Math.floor(t / 1000 % 60);
                        opts.daySelector.html($this.doubleNum(day));
                        opts.hourSelector.html($this.doubleNum(hour));
                        opts.minSelector.html($this.doubleNum(min));
                        opts.secSelector.html($this.doubleNum(sec));
                    } else {
                        $dom = opts.minSelector.parent().parent('.time-out');
                        if($dom) {
                            $dom.attr('href','javascript:;');
                            opts.minSelector.parent().remove();
                        }
                        //$this.afterAction(opts);
                        clearInterval(intervalDate);
                    }
                }, 1000);

            });
        },
        doubleNum: function (num) { //将个位数字变成两位
            if (num < 10) {
                return "0" + num;
            } else {
                return num + "";
            }
        },
        beforeAction: function (options) {

        },
        afterAction: function (options) {
            $(options.daySelector).parents('.count-down').hide();
        }

    });
})(jQuery);
/**********倒计时**********/
var $countDown = $('.count-down');
var arr = Array.prototype.slice.call($countDown);
arr.forEach(function (el) {
    var $el = $(el);
    var $end = moment(Number($el.data('end')) * 1000).format('YYYY/MM/DD HH:mm:ss');
    var now = moment().format('YYYY/MM/DD h:mm:ss');

    $el.countDown({
        startTimeStr: now,//开始时间
        endTimeStr: $end,//结束时间
        daySelector: $el.find('.day'),
        hourSelector: $el.find('.hour'),
        minSelector: $el.find('.min'),
        secSelector: $el.find('.sec')
    });
});