<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/



$router->get('/', function () use ($router) {
    /**
     * @var $routeCollection \Illuminate\Database\Eloquent\Collection
     */
    $routeCollection = $router->getRoutes();
    $routes = [];
    foreach ($routeCollection as $value) {
        $routes[] = [
            'method' => $value['method'],
            'uri'    => $value['uri']
        ];
    }

    return $routes;
});


$router->group(['prefix' => 'api/v1'], function () use ($router) {

    $router->post('/login', 'AuthController@login');

    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->group(['prefix' => 'post'], function () use ($router) {
            $router->get('/list', 'PostController@index');
            $router->post('/create', 'PostController@store');
        });
    }); 
   
});
