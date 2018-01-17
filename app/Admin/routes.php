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
});
