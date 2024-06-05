<?php

namespace Factory\PhpFramework\Router;

use InvalidArgumentException;

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
        $instance = self::getInstance();
        $normalizedUrl = $instance->normalizeUrl($url);

        // Check for duplicate routes
        foreach ($instance->routes as $route) {
            if ($route['method'] === $method && $instance->normalizeUrl($route['url']) === $normalizedUrl) {
                throw new InvalidArgumentException("Route already exists: $method $url");
            }
        }

        $url = self::getInstance()->baseUrl . $url;
        self::getInstance()->routes[] = [
            'method' => $method,
            'url' => $url,
            'callback' => $callback
        ];
    }

    /**
     * Normalize the URL by converting placeholders to a common format
     *
     * @param string $url The route URL
     * @return string
     */
    private function normalizeUrl(string $url): string
    {
        return preg_replace('/{[^\/]+}/', '{}', $url);
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
     * Add a new PUT route
     *
     * @param string $url The request URL
     * @param callable|string $callback The callback function
     * @return void
     */
    public static function put(string $url, callable|string $callback): void
    {
        self::add('PUT', $url, $callback);
    }

    /**
     * Add a new PATCH route
     *
     * @param string $url The request URL
     * @param callable|string $callback The callback function
     * @return void
     */
    public static function delete(string $url, callable|string $callback): void
    {
        self::add('DELETE', $url, $callback);
    }

    /**
     * Resolve the request
     *
     * @param Request $request The request object
     * @return mixed
     */
    public function resolve(Request $request): mixed
    {
        $exactMatch = null;
        $dynamicMatch = null;

        foreach ($this->routes as $route) {
            if ($route['method'] === $request->getMethod()) {
                if ($route['url'] === $request->getUrl()) {
                    $exactMatch = $route;
                    break;
                } elseif ($this->matchRoute($route['url'], $request->getUrl(), $params)) {
                    $dynamicMatch = ['route' => $route, 'params' => $params];
                }
            }
        }

        // If route has no placeholders
        if ($exactMatch) {
            http_response_code(200);
            if (is_string($exactMatch['callback'])) {
                return $this->resolveStringCallback($exactMatch['callback'], $request, []);
            }
            return call_user_func_array($exactMatch['callback'], [$request]);
        }

        // If route has placeholders
        if ($dynamicMatch) {
            http_response_code(200);
            if (is_string($dynamicMatch['route']['callback'])) {
                return $this->resolveStringCallback($dynamicMatch['route']['callback'], $request, $dynamicMatch['params']);
            }
            $request->addParams($dynamicMatch['params']);
            return call_user_func_array($dynamicMatch['route']['callback'], [$request]);
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
    private function convertToRegex(string $url): array|string|null
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