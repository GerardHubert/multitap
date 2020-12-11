<?php

declare(strict_types=1);

namespace App\View;

use App\Service\Http\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class View
{
    private $twig;
    private $session;
    private $filter;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $loader = new FilesystemLoader('../templates');
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $this->session);
        $this->filter = new \Twig\TwigFilter('htmlspecialchars_decode', 'htmlspecialchars_decode');
        $this->twig->addFilter($this->filter);
    }

    public function render(array $data): void
    {
        echo $this->twig->render("${data['path']}/${data['template']}.html.twig", $data['data']);
    }
}
