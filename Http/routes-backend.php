<?php


// URI: /{backend}/social
$router->group([
    'prefix'        => 'config/social',
    'middleware'    => 'hasPermission',
    'hasPermission' => 'manage@social_admin'
], function ($router) {

    $router->get('/', ['as' => 'admin.config.social', 'uses' => 'SocialManagerController@getIndex']);
});
