<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/26 0026
 * Time: 下午 3:41
 */

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\User;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Messages\Image;
use EasyWeChat\OfficialAccount\Application;

class WechatServerController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\BadRequestException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function index(Application $app)
    {
        $app->server->push(function ($message) {
            switch ($message['MsgType']) {
                case 'event':
                    return $this->_event($message['FromUserName'], $message['Event'], $message['EventKey']);
                    break;
                case 'text':
                    return $this->_text();
                    break;
            }
        });

        return $app->server->serve();
    }

    /**
     * 底部菜单
     * @param $app
     */
    public function _button($app)
    {
        $buttons = [
            [
                "type" => "view",
                "name" => "登录商城",
                "url"  => "http://www.meifusp.com/"
            ],[
                "type" => "view",
                "name" => "个人中心",
                "url"  => "http://www.meifusp.com/user"
            ],[
                "type" => "click",
                "name" => "联系客服",
                "key"  => "SERVICE"
            ]
        ];
        $app->menu->create($buttons);
    }

    /**
     * 接收文本推送
     * @return string
     */
    public function _text()
    {
        return '有问题请联系客服！';
    }

    /**
     * 接收事件推送
     * @param $FromUserName
     * @param $Event
     * @param $EventKey
     * @return mixed|string
     */
    public function _event($FromUserName, $Event, $EventKey)
    {
        switch (strtolower($Event)) {
            // 用户未关注扫推广码
            case 'subscribe':
                User::updateOrCreate(['openid' => $FromUserName],['subscribe' => 1]);
                //二维码扫描事件
                if($EventKey) {
                    return $this->subscribe($EventKey, $FromUserName);
                }
                return '欢迎关注美肤商城';
                break;
            // 点击微信菜单的链接
            case 'click':
                return new Image('7W--F2ZcFFNg3vLP7PWJdBt_9eR6idUhmyY51Th4Gt0');
                break;
        }
    }

    /**
     * 未关注分流操作
     * @param $key
     * @param $openid
     * @return mixed
     */
    public function subscribe($key, $openid)
    {
        $extension_id = str_replace('qrscene_', '', $key);
        $extension_user = User::find($extension_id);
        if($extension_user->type == 2) {
            $data = [
                $p_id = 0,
                $dealer_id = $extension_id
            ];
        } else {
            $data = [
                $p_id = $extension_id,
                $dealer_id = $extension_user->dealer_id
            ];
        }
        User::where('openid' ,$openid)->update($data);

        return "您已通过{$extension_user->nickname}的推荐关注过本公众号咯~点击<<a href='http://www.meifusp.com'>美肤商城</a>>挑选心仪产品，开启您的变美之旅！";
    }
}