<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware(['simulation', 'wechat.oauth:snsapi_userinfo', 'userinfo'])->group(function($route) {
    //商城首页
    $route::get('index/{pid?}', 'IndexController@index')->name('index');
    //商品详情页
    $route::get('product_details/{product}/{pid?}', 'IndexController@productDetails')->name('index.product_details');
    //免费领取列表或体验列表
    $route->get('product_list/{type}', 'IndexController@productList')->name('index.product_list');
    //判断用户是否购买套装后跳转
    $route->get('judge/{type}', 'IndexController@judge')->name('index.judge');

    //订单提交
    $route->post('order/{order?}', 'OrdersController@order')->name('index.order');
    //订单准备支付页面
    $route->get('order_ready_pay/{order}', 'OrdersController@orderReadyPay')->name('index.order_ready_pay');
    //提交完整订单
    $route->post('order_pay/{order}', 'OrdersController@orderPay')->name('index.order_pay');
    //支付宝准备支付页面
    $route->get('alipay_ready/{order}/{order_pay}', 'PayController@aliReady')->name('index.alipay_ready');
    //支付
    $route->get('pay/{type}/{order}/{order_pay}', 'PayController@pay')->name('index.pay');
    //我的地址
    $route->resource('address', 'AddressController');
    //选择地址
    $route->get('select_address/{address}', 'AddressController@selectAddress')->name('index.select_address');

    //用户中心
    $route->get('user', 'UserController@index')->name('index.user');
    //用户订单列表
    $route->get('order_list/{status?}/{confirm?}', 'OrderManagerController@orderList')->name('index.order_list');
    //订单详情
    $route->get('order_detail/{order}', 'OrderManagerController@orderDetail')->name('index.order_detail');
    //订单详情支付
    $route->get('order_detail_pay/{order_id}/{pay_type}', 'OrdersController@clickPay')->name('order_detail_pay');
    //订单状态操作
    $route->get('order_operation/{order}/{type}/{msg?}', 'OrdersController@orderOperation')->name('index.order_operation');
    //推广页面
    $route->get('reward', 'ExtensionController@index')->name('index.reward');
    //推广记录
    $route->get('reward_list/{type}/{status}', 'ExtensionController@rewardList')->name('index.reward_list');
    //推广海报
    $route->get('extension_page/{user}', 'ExtensionController@extensionPage')->name('index.extension_page');
    //推广规则
    $route->view('reward_rule', 'index.extension_rules')->name('index.reward_rule');
    //积分兑换页面
    $route->get('exchange', 'ExtensionController@exchange')->name('index.exchange');
    //积分兑换操作
    $route->post('exchange_operation', 'ExtensionController@exchangeOperation')->name('index.exchange_operation');
    //绑定提现账户页面
    $route->view('card_bind', 'index.card_bind')->name('index.card_bind');
});

//微信支付异步回调通知
Route::any('wechat_notify', 'PayNotifyController@wechatNotify')->name('wechat_notify');
//支付宝支付异步回调通知
Route::any('ali_notify', 'PayNotifyController@aliNotify')->name('ali_notify');
//支付宝支付同步通知
Route::any('ali_return', 'PayNotifyController@aliRetrun')->name('ali_return');
