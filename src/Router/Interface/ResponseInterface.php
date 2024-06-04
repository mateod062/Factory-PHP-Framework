<?php

namespace Factory\PhpFramework\Router\Interface;

interface ResponseInterface
{
    /**
     * Echo the response content
     *
     * @return void
     */
    public function send(): void;
}