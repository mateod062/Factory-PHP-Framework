<?php

use Factory\PhpFramework\Router\Router;

Router::get('/', 'IndexController@indexAction');
Router::get('/json', 'IndexController@indexJsonAction');
Router::get('/html', 'IndexController@indexHtmlAction');

Router::get('/events', 'EventController@index');
Router::get('/events/{id}', 'EventController@show');
Router::get('/events/create', 'EventController@create');
Router::post('/events', 'EventController@store');
Router::get('/events/edit/{id}', 'EventController@edit');
Router::post('/events/update/{id}', 'EventController@update');
Router::post('/events/delete/{id}', 'EventController@delete');
Router::post('/events/soft-delete/{id}', 'EventController@softDelete');
