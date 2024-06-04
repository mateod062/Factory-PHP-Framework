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
            'url' => $url,
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
                $request->addParams($params);
                return call_user_func_array($route['callback'], [$request]);
            }
        }

        return $this->default404Handler("Route not found");
    }

    /**
     * Helper function to confirm if a route matches the request URL
     *
     * @param string $routeUrl The route URL
     * @param string $requestUrl The request URL
     * @param mixed $params The route parameters
     * @return bool
     */
    private function matchRoute(string $routeUrl, string $requestUrl, mixed &$params): bool
    {
        $routePattern = $this->convertToRegex($routeUrl);
        if (preg_match('#^' . $routePattern . '$#', $requestUrl, $matches)) {
            array_shift($matches);
            // Extract placeholder names from route URL
            preg_match_all('/{([^\/]+)}/', $routeUrl, $placeholders);
            $placeholderNames = $placeholders[1];
            // Combine placeholder names with their values
            $params = array_combine($placeholderNames, $matches);

            return true;
        }
        return false;
    }

    /**
     * Convert a route URL to a regex pattern
     *
     * @param string $url The route URL
     * @return array|string|null
     */
    private function convertToRegex($url): array|string|null
    {
        // Replace placeholders with 'any' regex
        return preg_replace('/{[^\/]+}/', '([^\/]+)', $url);
    }

    /**
     * Resolve a string callback in the format 'Controller@method'
     *
     * @param string $callback The callback function
     * @param Request $request The request object
     * @param array $params The route parameters
     * @return mixed
     */
    private function resolveStringCallback(string $callback, Request $request, array $params): mixed
    {
        list($controller, $method) = explode('@', $callback);
        $controller = "Factory\\PhpFramework\\Controller\\$controller";
        if (class_exists($controller)) {
            $controller = new $controller();
            if (method_exists($controller, $method)) {
                $request->addParams($params);
                return call_user_func_array([$controller, $method], [$request]);
            }
        }

        return $this->default404Handler("Invalid route callback: $callback");
    }

    /**
     * Default 404 handler
     *
     * @param string $message The error message
     * @return JsonResponse
     */
    private function default404Handler(string $message): JsonResponse
    {
        http_response_code(404);
        return new JsonResponse(['error' => $message]);
    }
}