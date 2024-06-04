<?php

namespace Factory\PhpFramework\Controller;

use Factory\PhpFramework\Router\JsonResponse;
use Factory\PhpFramework\Router\Request;
use Factory\PhpFramework\Router\Response;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class IndexController
{
    private $twig;

public function __construct()
    {
        $loader = new FilesystemLoader(__DIR__ . '/../../templates');
        $this->twig = new Environment($loader);
    }

    public function indexAction(Request $request): Response
    {
        return new Response("Welcome to the index page!");
    }

    public function indexJsonAction(Request $request): JsonResponse
    {
        $data = ["message" => "Welcome to the index page!"];
        return new JsonResponse($data);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function indexHtmlAction(Request $request): Response
    {
        $html = $this->twig->render('index.html.twig', ['message' => 'Welcome to the index page!']);
        return new Response($html);
    }
}