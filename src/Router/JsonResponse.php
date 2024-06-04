<?php

namespace Factory\PhpFramework\Router;

use Factory\PhpFramework\Router\Interface\ResponseInterface;

class JsonResponse implements ResponseInterface
{
    public function __construct(private $data) {}

    /**
     * @inheritDoc
     */
    public function send(): void
    {
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}