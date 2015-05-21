<?php


// URI: /{backend}/social
$router->group(['prefix' => 'config/social'], function ($router) {
    $router->get('/', ['as' => 'admin.config.social', 'uses' => 'SocialManagerController@getIndex']);
});
