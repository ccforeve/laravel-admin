<?php

namespace App\Http\TraitFunction;

use App\Classes\Sms\SmsSender;

trait Notice
{
    //发送短信
    public static function sms( $appid, $appkey, $phone, $templId, $params, $notice_msg )
    {
        $sms = new SmsSender($appid, $appkey);
        $result = $sms->sendWithParam("86", $phone, $templId, $params);
        $arr = json_decode($result, true);
        //发送成功后记录到文件中
        if ( $arr[ 'errmsg' ] == 'OK' ) {
            $status = "发送成功【{$notice_msg}】";
            rwLog($phone, $status, 'notice_');
            return ['state' => 0, 'msg' => '发送成功'];
        } else {
            $status = "发送失败【{$notice_msg}】";
            rwLog($phone, $status, 'notice_');
            return ['state' => 401, 'msg' => '发送失败'];
        }
    }

}