<?php

namespace Factory\PhpFramework;

class Router
{
    private static ?Router $instance = null;
    public array $routes = [];
    private string $baseUrl = '/Factory-PHP-Framework';

    private function __construct() {}

    private function __clone() {}

    /**
     * Return the instance of the Router
     *
     * @return Router
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Add a new route
     *
     * @param string $method The HTTP method
     * @param string $url The request URL
     * @param callable $callback The callback function
     * @return void
     */
    public static function add(string $method, string $url, callable $callback): void
    {
        $url = self::getInstance()->baseUrl . $url;
        self::getInstance()->routes[] = compact('method', 'url', 'callback');
    }

    /**
     * Add a new GET route
     *
     * @param string $url The request URL
     * @param callable $callback The callback function
     * @return void
     */
    public static function get(string $url, callable $callback): void
    {
        self::add('GET', $url, $callback);
    }

    /**
     * Add a new POST route
     *
     * @param string $url The request URL
     * @param callable $callback The callback function
     * @return void
     */
    public static function post(string $url, callable $callback): void
    {
        self::add('POST', $url, $callback);
    }

    /**
     * Resolve the request
     *
     * @param Request $request The request object
     * @return mixed
     */
    public function resolve(Request $request): mixed
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $request->getMethod() && $route['url'] === $request->getUrl()) {
                http_response_code(200);
                return call_user_func($route['callback'], $request);
            }
        }
        http_response_code(404);
        return "Not Found";
    }
}