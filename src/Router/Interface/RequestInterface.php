<?php

namespace Factory\PhpFramework\Router\Interface;

interface RequestInterface
{
    /**
     * Get the request method
     *
     * @return string
     */
    public function getMethod(): string;

    /**
     * Get the request URL
     *
     * @return false|array|int|string
     */
    public function getUrl(): false|array|int|string;

    /**
     * Get a parameter by key
     *
     * @param string $key The parameter key
     * @return mixed
     */
    public function get(string $key): mixed;

    /**
     * Get the request parameters
     *
     * @return mixed
     */
    public function getParams(): mixed;

    /**
     * Get the request body
     *
     * @return mixed
     */
    public function getBody(): mixed;

    /**
     * Add parameters to the request
     *
     * @param array $params The parameters to add
     * @return void
     */
    public function addParams(array $params): void;
}