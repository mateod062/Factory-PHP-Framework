<?php

namespace Factory\PhpFramework\Controller;

use Factory\PhpFramework\Database\Connection;
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
    private Environment $twig;

    public function __construct()
        {
            $loader = new FilesystemLoader(__DIR__ . '/../../templates');
            $this->twig = new Environment($loader);
        }

    /**
     * Index action
     *
     * @param Request $request
     * @return Response
     */
    public function indexAction(Request $request): Response
    {
        return new Response("Welcome to the index page!");
    }

    /**
     * Index action in JSON format
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function indexJsonAction(Request $request): JsonResponse
    {
        $data = ["message" => "Welcome to the index page!"];
        return new JsonResponse($data);
    }

    /**
     * Index action in HTML format
     *
     * @param Request $request
     * @return Response|JsonResponse
     */
    public function indexHtmlAction(Request $request): Response|JsonResponse
    {
        try {
            $html = $this->twig->render('index.html.twig', ['message' => 'Welcome to the index page!']);
            return new Response($html);
        } catch (LoaderError | RuntimeError | SyntaxError $e) {
            return new JsonResponse(['error' => $e->getMessage()]);
        }
    }
}