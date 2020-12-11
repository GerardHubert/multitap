<?php

declare(strict_types=1);

namespace App\Controller\Backoffice;

use App\Model\Manager\UserManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\Token;
use App\View\View;

class UserController
{
    private $view;
    private $request;
    private $token;
    private $session;
    private $userManager;

    public function __construct(View $view, Request $request, Token $token, Session $session, UserManager $userManager)
    {
        $this->view = $view;
        $this->request = $request;
        $this->token = $token;
        $this->session = $session;
        $this->userManager = $userManager;
    }

    public function signInPage(): void
    {
        $this->token->setToken();

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'signIn',
            'data' => []
        ]);
    }

    public function newUserAction(): void
    {
        $sessionToken = $this->session->getToken();
        $inputToken = $this->request->cleanPost()['hidden_input_token'];

        if ($sessionToken === $inputToken) {
            $this->userManager->saveNewUser($this->request->cleanPost());
            header('Location: index.php?action=home');
            exit;
        }
         
        $this->session->setFlashMessage("Vous n'êtes pas autorisé à ouvrir un compte");
        header('Location: index.php?action=signInPage');
        exit;
    }

    public function logInPage(): void
    {
        $this->token->setToken();

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'logIn',
            'data' => []
        ]);
    }

    public function logInAction(): void
    {
        $sessionToken = $this->session->getToken();
        $inputToken = $this->request->cleanPost()['hidden_input_token'];

        if ($sessionToken === $inputToken) {
            $this->userManager->logIn($this->request->cleanPost());
        } else {
            $this->session->setFlashMessage('Vous n\'êtes pas autorisé à vous connecter');
            header('location: index.php?action=logInPage');
            exit;
        }
    }

    public function logOutAction(): void
    {
        $this->session->endSession();
        header('Location: index.php?action=home');
        exit;
    }
}
