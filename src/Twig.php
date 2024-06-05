<?php

namespace Factory\PhpFramework;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Loader\FilesystemLoader;

class Twig
{
    private static Environment $twig;

    /**
     * Render a template
     *
     * @param string $template
     * @param array $context
     * @return string
     */
    public static function render(string $template, array $context = []): string
    {
        try {
            if (!isset(self::$twig)) {
                $loader = new FilesystemLoader(__DIR__ . '/../templates');
                self::$twig = new Environment($loader);
            }

            return self::$twig->render($template, $context);
        } catch (LoaderError $e) {
            return 'Loader error: ' . $e->getMessage();
        } catch (RuntimeError $e) {
            return 'Runtime error: ' . $e->getMessage();
        } catch (SyntaxError $e) {
            return 'Syntax error: ' . $e->getMessage();
        }
    }
}