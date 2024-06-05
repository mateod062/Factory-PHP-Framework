<?php

use Factory\PhpFramework\Router\Router;

Router::get('/', 'IndexController@indexAction');
Router::get('/json', 'IndexController@indexJsonAction');
Router::get('/html', 'IndexController@indexHtmlAction');
Router::post('/insert', 'EventController@insertEventAction');
Router::post('/insert-many', 'EventController@batchInsertEventsAction');
Router::get('/fetch', 'EventController@fetchAllEventsAction');
Router::get('/fetch-one/num/{id}', 'EventController@fetchEventActionNum');
Router::get('/fetch-one/assoc/{id}', 'EventController@fetchEventActionAssoc');
Router::get('/update/{id}', 'EventController@updateEventAction');
