<?php

namespace Factory\PhpFramework\Router;

use Factory\PhpFramework\Router\Interface\RequestInterface;

class Request implements RequestInterface
{
    private array $params;

    public function __construct()
    {
        $this->params = array_merge($_GET, $_POST);
    }

    public function getMethod(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    public function getUrl(): false|array|int|string
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $path ?: '/';
    }

    public function get(string $key): mixed
    {
        return $this->params[$key] ?? null;
    }

    public function getBody(): mixed
    {
        $body = file_get_contents('php://input');
        return json_decode($body, true);
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function addParams(array $params): void
    {
        $this->params = array_merge($this->params, $params);
    }
}