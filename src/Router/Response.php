<?php

namespace Factory\PhpFramework\Router;

use Factory\PhpFramework\Router\Interface\ResponseInterface;

class Response implements ResponseInterface
{
    public function __construct(private $data) {}

    public function send(): void
    {
        echo $this->data;
    }
}