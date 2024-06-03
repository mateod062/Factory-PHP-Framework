<?php

namespace Factory\PhpFramework;

class Response implements ResponseInterface
{
    public function __construct(private $content) {}

    public function send(): void
    {
        echo $this->content;
    }
}