<?php

namespace Factory\PhpFramework\Router;

use Factory\PhpFramework\Router\Interface\ResponseInterface;

class JsonResponse implements ResponseInterface
{
    public function __construct(private $data, public $status = 200) {}

    /**
     * Send the response in JSON format
     */
    public function send(): void
    {
        http_response_code($this->status);
        header('Content-Type: application/json');
        echo json_encode($this->data);
    }
}