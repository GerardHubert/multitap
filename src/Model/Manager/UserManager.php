<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\User;
use App\Model\Repository\UserRepository;
use App\Service\Http\Session;

class UserManager
{
    private $userRepo;
    private $session;

    public function __construct(UserRepository $userRepo, Session $session)
    {
        $this->userRepo = $userRepo;
        $this->session = $session;
    }

    public function saveNewUser(array $form): void
    {
        $username = $form['username'];
        $email = $form['email'];
        $password = $form['password'];
        $passwordConfirm = $form['password_confirm'];
        $token = $form['hidden_input_token'];
        $this->session->deleteFlashMessage();
                
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPass(password_hash($passwordConfirm, PASSWORD_BCRYPT));
        $user->setUserRank('ROLE_MEMBER');
        $user->setIsActive('inactive');
        $user->setToken($token);
        $user->setSignInDate(time());

        $this->userRepo->create($user);
    }

    public function newToken(User $user, string $newToken, int $time): bool
    {
        $user->setToken($newToken);
        $user->setSignInDate($time);

        return $this->userRepo->updateToken($user);
    }

    public function activateUser(User $newUser): void
    {
        $newUser->setIsActive('active');
        $newUser->setSignInDate(0);
        $newUser->setToken('');

        $this->userRepo->updateIsActive($newUser);
    }

    public function logIn(array $logInForm): void
    {
        $user = $this->userRepo->findOneByUsername($logInForm['username']);
        switch ($user) {
            case null:
                $this->session->setFlashMessage('Nom d\'utilisateur inconnu et/ou mot de passe incorrect');
                header('Location: index.php?action=logInPage');
            break;
            case is_object($user):
                if (password_verify($logInForm['password'], $user->getPass()) === true && $user->getIsActive() === 'inactive') {
                    $this->session->setFlashMessage('Votre compte est inactif, connextion interdite');
                    header('Location: index.php?action=logInPage');
                    exit;
                } elseif ($user->getIsActive() === 'active' && password_verify($logInForm['password'], $user->getPass()) === true) {
                    $this->session->deleteFlashMessage();
                    $this->session->setUserId($user->getUserId());
                    $this->session->setUsername($user->getUsername());
                    $this->session->setUserRank($user->getUserRank());
                    header('Location: index.php?action=home');
                    exit;
                } elseif (password_verify($logInForm['password'], $user->getPass()) === false) {
                    $this->session->setFlashMessage('Nom d\'utilisateur inconnu et/ou mot de passe incorrect');
                    header('Location: index.php?action=logInPage');
                    exit;
                }
                
            break;
        }
    }

    public function showOneFromId(int $id): ?User
    {
        return $this->userRepo->findById($id);
    }

    public function showOneFromUsername(string $username): ?User
    {
        return $this->userRepo->findOneByUsername($username);
    }

    public function showAll(): ?array
    {
        return $this->userRepo->findByAll();
    }

    public function deleteUser(int $userId): bool
    {
        return $this->userRepo->delete($this->showOneFromId($userId));
    }

    public function updateUsername(User $user, array $form): bool
    {
        $usernameRegExp = "#^[A-Za-zéèçàâäêëîïôöòûüùñ_0-9]?[\s?\-?a-zéèçàâäêëîïôöòûüùñ_0-9]+?$#";
        $newUsername = $form['new_username'];
        $usernameValidation = preg_match($usernameRegExp, $newUsername);

        if ($usernameValidation === 0) {
            $this->session->setUsernameMessage('le nom est incorrect, certains caractères sont interdits');
            $this->session->deletePassMessage();
            $this->session->deleteMailMessage();
            header('Location: index.php?action=user_parameters_page');
            exit;
        }

        $this->session->deleteUsernameMessage();

        $user->setUsername($newUsername);
        return $this->userRepo->updateUsernameFromUser($user);
    }

    public function updateEmail(User $user, array $form): bool
    {
        $emailRegExp = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#";
        $newEmail = $form['new_email'];
        $emailValidation = preg_match($emailRegExp, $newEmail);

        if ($emailValidation === 0) {
            $this->session->setMailMessage('Cette adresse mail n\'est pas valide');
            $this->session->deleteUsernameMessage();
            $this->session->deletePassMessage();
            header('Location: index.php?action=user_parameters_page');
            exit;
        }

        $this->session->deleteMailMessage();

        $user->setEmail($newEmail);
        return $this->userRepo->updateEmailFromUser($user);
    }

    public function updatePassword(User $user, array $form): bool
    {
        if (password_verify($form['actual_pass'], $user->getPass()) === false) {
            $this->session->setPassMessage('mot de passe incorrect');
            $this->session->deleteUsernameMessage();
            $this->session->deleteMailMessage();
            header('Location: index.php?action=user_parameters_page');
            exit;
        };

        if (mb_strlen($form['new_pass']) < 6) {
            $this->session->setPassMessage('Le mot de passe chosi est trop court');
            header('Location: index.php?action=user_parameters_page');
            exit;
        } elseif ($form['new_pass'] !== $form['confirm_new_pass']) {
            $this->session->setPassMessage('Les mots de passe ne correspondent pas');
            header('Location: index.php?action=user_parameters_page');
            exit;
        }

        $this->session->deletePassMessage();
        $user->setPass(password_hash($form['new_pass'], PASSWORD_BCRYPT));
        return $this->userRepo->updatePassFromUser($user);
    }

    public function saveUserRequest(array $form, User $user): ?bool
    {
        $user->setUserDemand($form['new_role']);
        $user->setPreviousRank($user->getUserRank());

        return $this->userRepo->updateRankRequest($user);
    }

    public function updateRank(User $user): ?bool
    {
        $user->setUserRank($user->getUserDemand());
        $user->setUserDemand('none');
        return $this->userRepo->updateRankFromUser($user);
    }

    public function cancelRankDemand(User $user): ?bool
    {
        $user->setUserDemand('none');
        $user->setPreviousRank('');
        return $this->userRepo->updateRankRequest($user);
    }
}
