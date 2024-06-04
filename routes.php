<?php

use Factory\PhpFramework\Router\Router;

Router::get('/', 'IndexController@indexAction');
Router::get('/json', 'IndexController@indexJsonAction');
Router::get('/html', 'IndexController@indexHtmlAction');
