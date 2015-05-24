<?php

use Illuminate\Routing\Router;


// URI: /{backend}/social
$router->group([
    'prefix'        => 'config/social',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@social_admin'
], function (Router $router) {

    $router->get('/', ['as' => 'admin.config.social', 'uses' => 'SocialManagerController@getIndex']);
});


// URI: /{backend}/users
$router->group([
    'prefix'        => 'users',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@auth_user'
], function (Router $router) {

    $router->group(['prefix' => '{auth_user_id}', 'namespace' => 'User'], function (Router $router) {

        $router->group(['middleware' => 'hasPermission', 'hasPermission' => 'manage.update@auth_user'], function (Router $router) {

            $router->group(['prefix' => 'providers'], function (Router $router) {
                $router->post('/', ['uses' => 'ProvidersController@postForm']);
                $router->get('/', ['as' => 'admin.user.provider', 'uses' => 'ProvidersController@getForm']);
            });

        });
    });
});
