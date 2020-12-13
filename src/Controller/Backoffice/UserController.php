<?php

declare(strict_types=1);

namespace App\Controller\Backoffice;

use App\Model\Manager\ReviewManager;
use App\Model\Manager\UserManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\AccessControl;
use App\Service\Security\Token;
use App\View\View;
use PhpCsFixer\Fixer\FunctionNotation\VoidReturnFixer;

class UserController
{
    private $view;
    private $request;
    private $token;
    private $session;
    private $userManager;
    private $reviewManager;
    private $accessControl;

    public function __construct(View $view, Request $request, Token $token, Session $session, UserManager $userManager, AccessControl $accessControl, ReviewManager $reviewManager)
    {
        $this->view = $view;
        $this->request = $request;
        $this->token = $token;
        $this->session = $session;
        $this->userManager = $userManager;
        $this->accessControl = $accessControl;
        $this->reviewManager = $reviewManager;
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

    public function membersManagementPage(): void
    {
        if ($this->accessControl->isConnected() === true && $this->accessControl->getRole() === 'ROLE_ADMIN') {
            $users = $this->userManager->showAll();
            $this->view->render([
                'path' => 'backoffice',
                'template' => 'members',
                'data' => [
                    'users' => $users
                ]
            ]);
        } else {
            header('Location: index.php?action=logInPage');
            exit;
        }
    }

    public function deleteUserAction(): void
    {
        /***
         * Récupérer toutes les reviews postées par ce user
         * pour les set par un user anonyme
         * Puis supprimer le user en laissant faire le cascade delete qui supprimera les commentaires de ce user
         */

        $userToDeleteId = $this->request->cleanGet()['id'];
        $anonymousUser = $this->userManager->showOneFromUsername('Anonyme');
        $status = 0;
        $reviews = $this->reviewManager->showUserReviews((int) $userToDeleteId, $status);

        $this->reviewManager->setToAnonymousAction($reviews, $anonymousUser);

        $action = $this->userManager->deleteUser((int) $userToDeleteId);

        header('Location: index.php?action=members_management');
    }
}
