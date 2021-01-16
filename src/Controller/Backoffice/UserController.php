<?php

declare(strict_types=1);

namespace App\Controller\Backoffice;

use App\Model\Manager\ReviewManager;
use App\Model\Manager\UserManager;
use App\Service\Email;
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

    public function __construct(View $view, Request $request, Token $token, Session $session, UserManager $userManager, AccessControl $accessControl, ReviewManager $reviewManager, Email $email)
    {
        $this->view = $view;
        $this->request = $request;
        $this->token = $token;
        $this->session = $session;
        $this->userManager = $userManager;
        $this->accessControl = $accessControl;
        $this->reviewManager = $reviewManager;
        $this->email = $email;

        $this->checkActivity();
    }

    public function checkActivity(): void
    {
        /**
         * fonction pour vérifier quand a été faite la derniere requete de l'utilisateur
         * si la dernière requete a lieu un certain laps de temps après la précédente, 
         * on considère la session expirée, et on la détruit
         */

        if ($this->session->getLastMove() !== null && time() > $this->session->getLastMove() + 1800) {
                $this->session->endSession();
                header('Location: http://localhost:8000'.$_SERVER['REQUEST_URI']);
                exit;
        } elseif ($this->session->getLastMove() !== null && time() < $this->session->getLastMove() + 1800) {
            $this->session->setLastMove(time());
        }
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

    public function checkSignInForm(): void
    {
        /**
         * méthode pour vérifier le formulaire d'inscription
         */

        $signInForm = $this->request->cleanPost();

        if ($this->session->getToken() !== $signInForm['hidden_input_token']) {
            $this->session->setFlashMessage('Vous n\'avez pas l\'autorisation d\'accéder à cette page');
            header('Location: index.php?action=signInPage');
            exit;
        }

        /**
         * on vérifie d'abord que le username et l'adresse email ne figurent pas déjà en bdd
         */

        if ($this->userManager->showOneFromUsername($signInForm['username']) !== null) {
            $this->session->setFlashMessage('Ce nom d\'utilisateur est déjà pris');
            header('Location: index.php?action=signInPage');
            exit;
        } elseif ($this->userManager->showOneFromEmail($signInForm['email']) !== null) {
            $this->session->setFlashMessage('Cette adresse mail est déjà associé à un compte');
            header('Location: index.php?action=signInPage');
            exit;
        }

        /**
         * si le username est dispo, et que l'adresse mail n'existe pas déjà dans la bdd
         * on sauvegarde le user
         */

        $this->session->deleteFlashMessage();
        $this->userManager->saveNewUser($signInForm);
        $this->sendToken($signInForm);
    }

    public function sendToken($form): void
    {
        /**
         * méthode pour envoyer le token et afficher la page de vérification
         */

        $this->session->deleteFlashMessage();
        $newUser = $this->userManager->showOneFromUsername($form['username']);
        $this->email->sendInscriptionEmail($newUser);

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'confirmInscription',
            'data' => [
                'newUser' => $newUser
            ]
        ]);
    }

    public function sendNewToken(): void
    {
        if (empty($this->request->cleanPost()) || $this->request->cleanPost()['hidden_input_token'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Accès interdit');
            header('Location: index.php?action=logInPage');
            exit;
        }

        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);

        $this->token->setToken();
        $this->userManager->newToken($user, $this->session->getToken(), time());
        
        $this->email->sendInscriptionEmail($user);

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'confirmInscription',
            'data' => [
                'newUser' => $user
            ]
        ]);
    }

    public function sendNewTokenFromEmpty(): void
    {
        if (empty($this->request->cleanPost())) {
            header('Location: index.php?action=logInPage');
            exit;
        } elseif ($this->session->getToken() !== $this->request->cleanPost()['hidden_input_token']) {
            $this->session->setFlashMessage('Accès interdit');
            header('Location: index.php?action=new_token_page');
            exit;
        }

        $user = $this->userManager->showOneFromUsername($this->request->cleanPost()['username']);

        if ($user === null) {
            $this->session->setFlashMessage('Utilisateur non trouvé');
            header('Location: index.php?action=new_token_page');
            exit;
        } elseif ($user->getIsActive() === 'active') {
            $this->session->setFlashMessage('Votre compte utilisateur est déjà actif, essayez de vous connecter');
            header('Location: index.php?action=logInPage');
            exit;
        }

        switch ($this->userManager->checkUsernameAndEmail($user, $this->request->cleanPost())) {
            case false:
                $this->session->setFlashMessage('L\'adresse email saisie n\'est pas la même que celle enregistrée dans nos données');
                header('Location: index.php?action=new_token_page');
            break;
            case true:
                $this->session->deleteFlashMessage();
                $this->userManager->newToken($user, $this->session->getToken(), time());
                $this->sendToken($this->request->cleanPost());
            break;
        }
    }

    public function checkToken(): void
    {
        $this->session->deleteFlashMessage();
        $newUser = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $clickedAt = time();
        $expiration = $newUser->getSignInDate() + 60*5;

        /**
         * Si le token a expiré : proposer l'envoi d'un nouveau token
         * et passer le nouvel objet user créé
         */

        if ($clickedAt > $expiration) {
            $this->session->setFlashMessage('Le token a expiré');
            $this->newTokenFromPrefilledForm($newUser);
            exit;

        /**
         * si le token est OK
         */
        } elseif ($this->request->cleanPost()['token_check'] === $newUser->getToken()) {
            $this->userManager->activateUser($newUser);
            $this->session->setFlashMessage('Votre compte est actif. Vous pouvez vous connecter.');
            header('Location: index.php?action=logInPage');
            exit;

        /**
         * Si le token n'a pas expiré mais qu'ils ne correspondent pas
         */
        } elseif ($this->request->cleanPost()['token_check'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Le jeton ne correspond pas');
            $this->newTokenFromPrefilledForm($newUser);
            exit;
        }
    }

    public function newTokenFromPrefilledForm($newUser): void
    {
        /**
         * Page pour générer et envoyer un nouveau token par mail
         * à partir d'un formulaire pré rempli
         */

        $this->token->setToken();

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'prefilledFormToken',
            'data' => [
                'newUser' => $newUser
                ]
        ]);
    }

    /**
     * si le token a expiré, on redirige vers un formulaire prérempli pour avoir un nouveau token
     * limiter l'accès à cette page au user qui demande un nouveau token
     */

    public function sendTokenFromPreFilledForm(): void
    {
        if ($this->request->cleanPost()['hidden_input_token'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Accès à cette page non autorisée');
            header('Location:');
            exit;
        }

        $this->session->deleteFlashMessage();
        
        $newUser = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $newTime = time();
        $newToken = $this->request->cleanPost()['hidden_input_token'];
        $this->userManager->newToken($newUser, $newToken, $newTime);

        $this->email->sendInscriptionEmail($newUser);

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'confirmInscription',
            'data' => [
                'newUser' => $newUser
            ]
        ]);
    }

    public function newTokenPage(): void
    {
        $this->token->setToken();
        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'newTokenPage',
            'data' => []
        ]);
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
        $user = $this->userManager->showOneFromUsername($this->session->getUsername());

        if ($user->getUserDemand() === 'none' && $user->getUserRank() !== $user->getPreviousRank()) {
            $this->userManager->cancelRankDemand($user);
        }

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

    public function inactivateUserAction(): void 
    {
        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $this->userManager->inactivateUser($user);
        header('Location: index.php?action=members_management');
        exit;
    }

    public function activateUserAction(): void
    {
        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $this->userManager->activateUserFromAdmin($user);
        header('Location: index.php?action=members_management');
        exit;
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
        $actualRole = $user->getUserRank();
        $previousRole = $user->getPreviousRank();
        $demand = $user->getUserDemand();

        /**
         * une demande de changement acceptée
         * si userDemand est à none, et que previousRole !== du role actuel
         */

        if ($demand === 'none' && !empty($previousRole) && $actualRole !== $previousRole) {
            $this->session->setGrantedMessage('Félicitations, votre rôle a évolué. Vous êtes désormais ' . mb_substr($this->session->getUserRank(), 5));
        }

        /**
         * si le user fait sa demande et reste dans la même session, message = 'demande prise en compte'
         * si nouvelle session et demande toujours en cours, message = 'demande en cours' en comparant userDemand, actualRank et PreviousRank
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
        //$this->cookies->setRole((string) $this->session->getUserRank()); on sauvegarde dans la table users au lieu d'un cookie
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

    public function newPassDemand(): void
    {
        $this->token->setToken();

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'forgottenPassword',
            'data' => []
        ]);
    }

    public function resetPassLink(): void
    {
        /**
         * si le formulaire est accpeté, on cherche un utilisateur à partir de l'email saisi
         */

        if (empty($this->session->getToken()) || $this->request->cleanPost()['hidden_input_token'] !== $this->session->getToken()) {
            $this->session->setFlashMessage('Accès interdit');
            header('Location: index.php?action=forgotten_password');
            exit;
        }

        $user = $this->userManager->showOneFromEmail($this->request->cleanPost()['email']);

        switch ($user) {
            case null:
                $this->session->setFlashMessage('Aucun utilisateur trouvé avec cette adresse mail');
                header('Location: index.php?action=forgotten_password');
            break;
            case is_object($user):
                $this->userManager->newToken($user, $this->request->cleanPost()['hidden_input_token'], time());
                $this->email->sendResetPassLink($user);
                $this->session->setFlashMessage('Un email  pour réinitialiser votre mot de passe a été envoyé à l\'adresse indiquée');
                header('Location: index.php?action=forgotten_password');
            break;
        }
    }

    public function resetPassPage(): void
    {
        /**
         * on vérifie qu'au click, le token n'est pas périmé
         * on vérifie que le token passé dans l'url correspond bien au token en bdd concernant ce user précis
         */

        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);

        if (time() > $user->getSignInDate() + 60 * 5) {
            $this->session->setFlashMessage('Le token a expiré. Demandez un nouvel email');
            header('Location: index.php?action=forgotten_password');
            exit;
        }

        switch ($user) {
            case null:
                $this->session->setFlashMessage('Aucun user trouvé avec cet Id');
                header('Location: index.php?action=forgotten_password');
            break;
            case is_object($user):
                $this->session->deleteFlashMessage();
                $this->token->setToken();
                $this->view->render([
                    'path' => 'frontoffice',
                    'template' => 'resetPassPage',
                    'data' => [
                        'user' => $user
                    ]
                ]);
            break;
        }
    }

    public function resetPassAction(): void
    {
        $user = $this->userManager->showOneFromId((int) $this->request->cleanGet()['id']);

        if ($this->session->getToken() !== $this->request->cleanPost()['hidden_input_token']) {
            $this->session->setFlashMessage('Accès interdit');
            header('Location: index.php?action=reset_pass_page');
            exit;
        } elseif ($this->request->cleanPost()['new_pass'] !== $this->request->cleanPost()['confirm_new_pass']) {
            $this->session->setFlashMessage('Les mots de passe ne correspondent pas');
            header('Location: index.php?action=reset_pass_page&id='.$user->getUserId());
            exit;
        }

        if ($this->userManager->resetPass($user, $this->request->cleanPost()) === false) {
            $this->session->setFlashMessage('Une erreur est survenue');
            header('Location: index.php?action=reset_pass_page&id='.$user->getUserId());
            exit;
        };

        $this->session->endSession();
        header('Location: index.php?action=logInPage');
        exit;
    }
}
