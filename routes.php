<?php

use Factory\PhpFramework\Router\Router;

Router::get('/', 'IndexController@indexAction');
Router::get('/json', 'IndexController@indexJsonAction');
Router::get('/html', 'IndexController@indexHtmlAction');
Router::post('/insert', 'EventController@insertEventAction');
Router::post('/insert-many', 'EventController@batchInsertEventsAction');
Router::get('/fetch', 'EventController@fetchAllEventsAction');
Router::get('/fetch-one/{id}', 'EventController@fetchEventAction');
Router::get('/update/{id}', 'EventController@updateEventAction');
