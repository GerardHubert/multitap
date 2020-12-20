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

        if ($this->session->getUserRank() !== "ROLE_ADMIN") {
            $this->session->endSession();
            header('Location: index.php?action=signInPage');
        }

        header('Location: index.php?action=members_management');
    }

    public function userParametersPage(): void
    {
        if ($this->accessControl->isConnected() === false) {
            header('Location: index.php?action=logInPage');
            exit;
        }

        $this->token->setToken();

        $user = $this->userManager->showOneFromId($this->session->getUserId());
        $this->token->setToken();
        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'userParametersPage',
            'data' => [
                'user' => $user
            ]
        ]);
    }

    public function updateUsernameAndEmail(): void
    {
        if ($this->request->cleanPost()['token'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Vous ne disposez pas des droits nécessaires');
            header('Location: index.php?action=user_parameters_page');
            exit;
        }

        if (!empty($this->request->cleanPost()['new_username'])) {
            $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
            $this->userManager->updateUsername($user, $this->request->cleanPost());
            $this->session->endSession();
            header('Location: index.php?action=user_parameters_page');
            exit;
        }   elseif (!empty($this->request->cleanpost()['new_email'])) {
                $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
                $this->userManager->updateEmail($user, $this->request->cleanPost());
                header('Location: index.php?action=user_parameters_page');
                exit;
        }
    }

    /*public function updateUsernameAction(): void
    {
        if ($this->request->cleanPost()['token'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Vous ne disposez pas des droits nécessaires');
            header('Location: index.php?action=user_parameters_page');
            exit;
        }

        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $this->userManager->updateUsername($user, $this->request->cleanPost());
        $this->session->endSession();
        header('Location: index.php?action=user_parameters_page');
        exit;
    }

    public function updateEmailAction(): void
    {
        if ($this->request->cleanPost()['token'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Vous ne disposez pas des droits nécessaires');
            header('Location: index.php?action=user_parameters_page');
            exit;
        }
        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $this->userManager->updateEmail($user, $this->request->cleanPost());
        header('Location: index.php?action=user_parameter_page');
        exit;
    }*/

    public function updatePasswordAction(): void
    {
        if ($this->request->cleanPost()['token'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Vous ne disposez pas des droits nécessaires');
            header('Location: index.php?action=user_parameters_page');
            exit;
        }
        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $this->userManager->updatePassword($user, $this->request->cleanPost());
        $this->session->endSession();
        header('Location: index.php?action=logInPage');
        exit;
    }

    public function updateRankDemand(): void
    {
        if ($this->session->getToken() !== $this->request->cleanPost()['hidden_input_token']) {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

        $this->userManager->saveUserRequest($this->request->cleanPost(), $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']));
        $this->session->setFlashMessage('Votre demande de changement de rôle a bien été prise en compte');
        header('Location: index.php?action=user_parameters_page');
        exit;
    }

    public function updateRankValidation(): void
    {
        $this->userManager->updateRank($this->userManager->showOneFromId((int) $this->request->cleanGet()['id']));
        header('Location: index.php?action=members_management');
        exit;
    }

    public function updateRankCancel(): void
    {
        $this->userManager->cancelRankDemand($this->userManager->showOneFromId((int) $this->request->cleanGet()['id']));
        header('Location: index.php?action=members_management');
        exit;

    }
}
