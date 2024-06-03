<?php

use Factory\PhpFramework\Router;

Router::add('GET', '/about', function($request) {
    return "About Us";
});
Router::get('/', function($request) {
    return "Welcome";
});
Router::post('/contact', function($request) {
    return "Contact Us";
});
