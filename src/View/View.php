<?php

declare(strict_types=1);

namespace App\View;

use App\Model\Manager\ReviewManager;
use App\Model\Manager\UserManager;
use App\Service\Http\Session;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

final class View
{
    private $twig;
    private $session;
    private $filter;
    private $userManager;
    private $reviewManager;

    public function __construct(Session $session, UserManager $userManager, ReviewManager $reviewManager)
    {
        $this->session = $session;
        $this->userManager = $userManager;
        $this->reviewManager = $reviewManager;

        $loader = new FilesystemLoader('../templates');
        $this->twig = new Environment($loader);
        $this->twig->addGlobal('session', $this->session);
        $this->twig->addGlobal('usersList', $this->userManager->showAll());
        $this->twig->addGlobal('reviewsListTwo', $this->reviewManager->showAllFromStatus(2));
        $this->filter = new \Twig\TwigFilter('htmlspecialchars_decode', 'htmlspecialchars_decode');
        $this->twig->addFilter($this->filter);
    }

    public function render(array $data): void
    {
        echo $this->twig->render("${data['path']}/${data['template']}.html.twig", $data['data']);
    }
}
