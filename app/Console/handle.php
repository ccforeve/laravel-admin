<?php

/**
 * 记录发货和开通会员成功后发送短信的信息
 * @param null $phone
 * @param null $status
 * @param string $type
 */
function rwLog($phone = null, $status=null, $type = ''){
    $filename = storage_path('logs/sms/').$type.date("Y-m-d").".log";
    if(file_exists($filename)){
        /*数组写入*/
        $arr = array('phone'=>$phone,'message'=>$status,'time'=>date("Y-m-d H:i:s"));
        file_put_contents($filename, print_r ($arr,true),FILE_APPEND);/*FILE_APPEND:追加文件写入*/
    }else{
        fopen($filename, "w");/*创建log文件*/
        $arr = array('phone'=>$phone,'message'=>$status,'time'=>date("Y-m-d H:i:s"));
        file_put_contents($filename, print_r ($arr,true),FILE_APPEND);/*FILE_APPEND:追加文件写入*/
    }
}