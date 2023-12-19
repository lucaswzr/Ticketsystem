<?php

namespace APP\classes;


use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;

class twigRenderer
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates');

        $this->twig = new \Twig\Environment($loader);
    }

    /**
     * @throws SyntaxError
     * @throws RuntimeError
     * @throws LoaderError
     */
    public function render($templateName, $data = []): string
    {
        return $this->twig->render($templateName, $data);
    }
}