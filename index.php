<?php

require 'vendor/autoload.php';

use Factory\PhpFramework\Router\Request;
use Factory\PhpFramework\Router\Response;
use Factory\PhpFramework\Router\Router;

// Create the request
$request = new Request();
// Create router instance
$router = Router::getInstance();

require 'routes.php';

// Resolve the request
$response = $router->resolve($request);
// Echo the response content
$response->send();