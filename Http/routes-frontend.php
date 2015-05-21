<?php

$router->group(['prefix' => 'login'], function ($router) {
    // Authentication
    $router->get('{provider}', ['as' => 'pxcms.user.provider', 'uses' => 'AuthController@loginThirdParty']);
    $router->get('/', ['as' => 'pxcms.user.login', 'uses' => 'AuthController@getLogin']);
});
