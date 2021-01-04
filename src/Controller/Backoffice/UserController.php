<?php

declare(strict_types=1);

namespace App\Controller\Backoffice;

use App\Model\Manager\ReviewManager;
use App\Model\Manager\UserManager;
use App\Service\Email;
use App\Service\Http\Cookies;
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
    private $email;
    private $cookies;

    public function __construct(View $view, Request $request, Token $token, Session $session, UserManager $userManager, AccessControl $accessControl, ReviewManager $reviewManager, Email $email, Cookies $cookies)
    {
        $this->view = $view;
        $this->request = $request;
        $this->token = $token;
        $this->session = $session;
        $this->userManager = $userManager;
        $this->accessControl = $accessControl;
        $this->reviewManager = $reviewManager;
        $this->email = $email;
        $this->cookies = $cookies;
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

    public function confirmationPage(): void
    {
        $username = $this->request->cleanPost()['username'];
        $searchResult = $this->userManager->showOneFromUsername($username);

        /**
         * on vérifie d'abord que le username n'est pas déjà pris
         */

        if ($searchResult !== null) {
            $this->session->setFlashMessage('Ce nom d\'utilisateur est déjà pris');
            header('Location: index.php?action=signInPage');
            exit;
        }

        /**
         * si le username choisi est dispo
         * on crée le nouveau user et on envoie le token par mail
         */

        $this->session->deleteFlashMessage();

        /*$creation = time();
        $expiration = $creation + (60*5);
        $this->session->setTokenExpiration($expiration);
        $this->session->setInscriptionForm($this->request->cleanPost());*/
        $this->userManager->saveNewUser($this->request->cleanPost());
        $newUser = $this->userManager->showOneFromUsername($this->request->cleanPost()['username']);
        $this->email->sendInscriptionEmail($this->request->cleanPost());

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'confirmInscription',
            'data' => [
                'newUser' => $newUser
            ]
        ]);
    }

    public function checkToken(): void
    {
        $newUser = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $clickedAt = time();
        $expiration = $newUser->getSignInDate() + 5;

        /**
         * Si le token a expiré : proposer l'envoi d'un nouveau token
         * et passer le nouvel objet user créé
         */

        if ($clickedAt > $expiration) {

            $this->session->setFlashMessage('Le token a expiré'); 
            $this->sendNewToken($newUser);
            //header('location: index.php?action=new_token_page&id=' . $newUser->getuserId());
            //$this->session->endSession();
            exit;

        /**
         * si le token est OK 
         */

        } elseif ($this->request->cleanPost()['token_check'] === $newUser->getToken()) {

            //$this->userManager->saveNewUser($this->session->getInscriptionForm());
            $this->userManager->activateUser($newUser);
            $this->session->setFlashMessage('Votre compte a bien été créé. Veuillez vous connecter.');
            header('Location: index.php?action=logInPage');
            exit;

        /**
         * Si le token n'a pas expiré mais qu'ils ne correspondent pas
         */

        } elseif ($this->request->cleanPost()['token_check'] !== $this->session->getToken()) {

            $this->session->setFlashMessage('Le token ne correspond pas');
            header('Location: index.php?action=signInPage');
            $this->session->endSession();
            exit;
        }
    }

    public function sendNewToken($newUser): void
    {
        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'newTokenPage',
            'data' => [
                'newUser' => $newUser
                ]
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
        //controle d'acces
        if ($this->accessControl->isConnected() === false) {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

        /*
         * Récupérer toutes les reviews postées par ce user
         * pour les set par un user anonyme
         * Puis supprimer le user en laissant faire le cascade delete qui supprimera les commentaires de ce user
        */

        $userToDeleteId = $this->request->cleanGet()['id'];
        $anonymousUser = $this->userManager->showOneFromUsername('Anonyme');
        $status = 0;
        $reviews = $this->reviewManager->showUserReviews((int) $userToDeleteId, $status);

        $this->reviewManager->setToAnonymousAction($reviews, $anonymousUser);

        $this->userManager->deleteUser((int) $userToDeleteId);

        if ($this->session->getUserRank() !== "ROLE_ADMIN") {
            $this->session->endSession();
            header('Location: index.php?action=home');
        }

        header('Location: index.php?action=members_management');
    }

    public function userParametersPage(): void
    {
        if ($this->accessControl->isConnected() === false) {
            header('Location: index.php?action=logInPage');
            exit;
        }

        $user = $this->userManager->showOneFromId($this->session->getUserId());

        /**
         * on vérifie si le role actuel du user est différent de son role depuis sa dernière connexion, stockée dans un cookie
         * si le rôle est différent, ça veut dire que sa demande de changement de role a été acceptée, et on affiche un message
         */

        if ($this->cookies->getRole() !== null && $this->cookies->getRole() !== $this->session->getUserRank()) {
            $this->session->setGrantedMessage('Félicitations, votre rôle a évolué. Vous êtes désormais ' . mb_substr($this->session->getUserRank(), 5));
            echo 'role en cookie : ' . $this->cookies->getRole() . '<br>';
            echo 'role en session : ' . $this->session->getUserRank() . '<br>';
        }

        /**
         * si le user fait sa demande et reste dans la même session, message = 'demande prise en compte'
         * si nouvelle session et demande toujours en cours, message = 'demande en cours'
         */

        if ($this->session->getRecorded() === null) {
            if ($user->getUserDemand() !== 'none') {
                $this->session->setRankMessage('En cours : changement de rôle vers ' . mb_substr($user->getUserDemand(), 5));
            } elseif ($user->getUserDemand() === 'none') {
                $this->session->deleteRankMessage();
            }
        }


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
        if ($this->accessControl->isConnected() === false) {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

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
        } elseif (!empty($this->request->cleanpost()['new_email'])) {
            $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
            $this->userManager->updateEmail($user, $this->request->cleanPost());
            header('Location: index.php?action=user_parameters_page');
            exit;
        }
    }

    public function updatePasswordAction(): void
    {
        if ($this->accessControl->isConnected() === false || $this->request->cleanPost()['token'] !== $this->session->getToken()) {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
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
        if ($this->accessControl->isConnected() === false || $this->session->getToken() !== $this->request->cleanPost()['hidden_input_token']) {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

        $this->userManager->saveUserRequest($this->request->cleanPost(), $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']));
        $this->session->setRecorded('Demande vers rôle ' . mb_substr($this->request->cleanPost()['new_role'], 5) . ' enregistrée.');
        $this->cookies->setRole((string) $this->session->getUserRank());
        header('Location: index.php?action=user_parameters_page');
        exit;
    }

    public function updateRankValidation(): void
    {
        if ($this->accessControl->isConnected() === false || $this->accessControl->getRole() !== 'ROLE_ADMIN') {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

        $this->userManager->updateRank($this->userManager->showOneFromId((int) $this->request->cleanGet()['id']));
        header('Location: index.php?action=members_management');
        exit;
    }

    public function updateRankCancel(): void
    {
        if ($this->accessControl->isConnected() === false || $this->accessControl->getRole() !== 'ROLE_ADMIN') {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

        $this->userManager->cancelRankDemand($this->userManager->showOneFromId((int) $this->request->cleanGet()['id']));
        header('Location: index.php?action=members_management');
        exit;
    }
}
