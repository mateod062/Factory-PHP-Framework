<?php

require 'vendor/autoload.php';

use Factory\PhpFramework\Router;
use Factory\PhpFramework\Request;
use Factory\PhpFramework\Response;

// Create the request
$request = new Request();
// Create router instance
$router = Router::getInstance();

require 'routes.php';

// Resolve the request
$responseContent = $router->resolve($request);
// Create the response with the result of the router
$response = new Response($responseContent);
// Echo the response content
$response->send();