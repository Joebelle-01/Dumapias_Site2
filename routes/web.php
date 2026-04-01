<?php

/** @var \Laravel\Lumen\Routing\Router $router */

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// API routes
$router->group(['prefix' => 'api'], function () use ($router) {
    $router->get('/users', 'UserController@index');     // Get all users
    $router->post('/users', 'UserController@add');      // Create new user
    $router->get('/users/{id}', 'UserController@show'); // Get user by ID
    $router->put('/users/{id}', 'UserController@update'); // Update user
    $router->patch('/users/{id}', 'UserController@update'); // Partial update
    $router->delete('/users/{id}', 'UserController@delete'); // Delete user
});

// Debug route - NOW CHECKING CORRECT MODEL LOCATION
$router->get('/debug', function () use ($router) {
    return [
        'php_version' => phpversion(),
        'app_debug' => env('APP_DEBUG'),
        'db_connection' => env('DB_CONNECTION'),
        'db_database' => env('DB_DATABASE'),
        'user_model_exists' => class_exists('App\Models\User'),  
        'user_controller_exists' => class_exists('App\Http\Controllers\UserController'),
        'api_responder_exists' => trait_exists('App\Traits\ApiResponder'),
    ];
});