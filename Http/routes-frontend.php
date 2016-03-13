<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => 'login'], function (Router $router) {
    // Authentication

    $router->group(['prefix' => '{provider}'], function (Router $router) {

        $router->get('remove', ['as' => 'pxcms.user.remove_provider', 'uses' => 'AuthController@removeProvider']);
        $router->get('/', ['as' => 'pxcms.user.provider', 'uses' => 'AuthController@loginThirdParty']);
    });

    $router->get('/', ['as' => 'pxcms.user.login', 'uses' => 'AuthController@getLogin']);
});

// Add support to the user control panel
$router->group(['prefix' => 'user', 'namespace' => 'ControlPanel', 'middleware' => 'auth'], function (Router $router) {

    $router->group(['prefix' => 'provider'], function (Router $router) {
        $router->post('/', ['uses' => 'ProviderController@postForm']);
        $router->get('/', ['as' => 'pxcms.user.provider_settings', 'uses' => 'ProviderController@getForm']);
    });
});
