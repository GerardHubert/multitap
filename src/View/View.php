<?php

declare(strict_types=1);

namespace App\View;

use App\Service\Http\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class View
{
    /*Environment*/ private $twig;
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
        $loader = new FilesystemLoader('../templates');
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $this->session);
    }

    public function render(array $data): void
    {
        echo $this->twig->render("frontoffice/${data['template']}.html.twig", $data['data']);
    }
}
