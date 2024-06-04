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
     * Get the request parameters
     *
     * @return array
     */
    public function getParams(): array;
}