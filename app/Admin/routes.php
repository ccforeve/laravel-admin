<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    $router->get('become_dealer/{user}', 'UserController@becomeDealer')->name('become_dealer');

    $router->resource('users', UserController::class);

    $router->resource('products', ProductsController::class);

    $router->resource('spec', SpecificationsController::class);

    //修改收货地址
    $router->post('edit_address/{address}', 'OrderController@editAddress')->name('admin.edit_address');
    //添加快递信息
    $router->post('delivery/{order}', 'OrderController@delivery')->name('admin.delivery');
    //修改快递信息
    $router->post('edit_express/{logistic}', 'OrderController@editExpress')->name('admin.edit_express');
    //查看详情信息
    $router->get('order_detail/{order}', 'OrderController@detail')->name('admin.order_detail');
    //查看实时物流信息
    $router->get('see_express/{logistic}', 'OrderController@seeExpress')->name('admin.see_express');


    $router->resource('order_list', OrderController::class);


});
