<?php

namespace Factory\PhpFramework;

interface ResponseInterface
{
    /**
     * Echo the response content
     *
     * @return void
     */
    public function send(): void;
}