<?php

namespace Factory\PhpFramework\Router;

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
     * @param callable|string $callback The callback function
     * @return void
     */
    public static function add(string $method, string $url, callable|string $callback): void
    {
        $url = self::getInstance()->baseUrl . $url;
        self::getInstance()->routes[] = [
            'method' => $method,
            'url' => (new Router)->convertToRegex($url),
            'callback' => $callback
        ];
    }

    /**
     * Add a new GET route
     *
     * @param string $url The request URL
     * @param callable|string $callback The callback function
     * @return void
     */
    public static function get(string $url, callable|string $callback): void
    {
        self::add('GET', $url, $callback);
    }

    /**
     * Add a new POST route
     *
     * @param string $url The request URL
     * @param callable|string $callback The callback function
     * @return void
     */
    public static function post(string $url, callable|string $callback): void
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
            if ($this->matchRoute($route['url'], $request->getUrl(), $params) && $route['method'] === $request->getMethod()) {
                http_response_code(200);
                if (is_string($route['callback'])) {
                    return $this->resolveStringCallback($route['callback'], $request, $params);
                }
                return call_user_func_array($route['callback'], array_merge([$request], $params));
            }
        }

        return $this->default404Handler("Route not found");
    }

    private function matchRoute($routeUrl, $requestUrl, &$params): bool
    {
        $routePattern = $this->convertToRegex($routeUrl);
        if (preg_match('#^' . $routePattern . '$#', $requestUrl, $matches)) {
            array_shift($matches);
            $params = $matches;
            return true;
        }
        return false;
    }

    private function convertToRegex($url): array|string|null
    {
        return preg_replace('/{[^\/]+}/', '([^\/]+)', $url);
    }

    private function resolveStringCallback($callback, $request, $params): mixed
    {
        list($controller, $method) = explode('@', $callback);
        $controller = "Factory\\PhpFramework\\Controller\\$controller";
        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $method)) {
                return call_user_func_array([$controller, $method], array_merge([$request], $params));
            }
        }

        return $this->default404Handler("Invalid route callback: $callback");
    }

    private function default404Handler(string $message): JsonResponse
    {
        http_response_code(404);
        return new JsonResponse(['error' => $message]);
    }
}