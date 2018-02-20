<?php

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

/**
 * Routes work without authentication
 */
$router->post('signin', 'ApiController@signin');
$router->post('signup', 'ApiController@signup');
$router->get('/recipes/', 'RecipeController@list');


/**
 * Routes require authentication
 */
$router->group(['middleware' => 'auth'], function () use ($router) {
    $router->post('/recipes/', 'RecipeController@create');
    $router->patch('/recipes/{id}', 'RecipeController@update');
    $router->delete('/recipes/{id}', 'RecipeController@delete');
    $router->post('/images/', 'ApiController@upload');

});

