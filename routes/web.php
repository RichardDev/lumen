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

$router->get('/', function () use ($router) {
    return "<< -- Swapi api planets -- >>";    
});

$router->get('/planetas', 'PlanetaController@index');
$router->post('/planetas/create', 'PlanetaController@create');
$router->post('/planetas/delete', 'PlanetaController@delete');
$router->get('/planetas/id/{id}', 'PlanetaController@viewByID');
$router->get('/planetas/nome/{nome}', 'PlanetaController@viewByName');
