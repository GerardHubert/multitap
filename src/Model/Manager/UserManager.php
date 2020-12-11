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
        $usernameRegExp = "#^[a-zéèçàâäêëîïôöòûüùñ_0-9]?[\s?\-?a-zéèçàâäêëîïôöòûüùñ_0-9]+?$#";
        $emailRegExp = "#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#";

        $usernameValidation = preg_match($usernameRegExp, $username);
        $emailValidation = preg_match($emailRegExp, $email);
            
        if ($usernameValidation === 0) {
            $this->session->setFlashMessage('le nom est incorrect, certains caractères sont interdits');
            header('Location: index.php?action=signInPage');
            exit;
        } elseif ($this->userRepo->findOneByUsername($username) !== null) {
            $this->session->setFlashMessage('désolé, ce pseudo est déjà utilisé');
            header('Location: index.php?action=signInPage');
            exit;
        } elseif ($emailValidation === 0) {
            $this->session->setFlashMessage('le format email est incorrect');
            header('Location: index.php?action=signInPage');
            exit;
        } elseif ($password !== $passwordConfirm) {
            $this->session->setFlashMessage('les mots de passe ne correspondent pas');
            header('Location: index.php?action=signInPage');
            exit;
        }
             
        $this->session->deleteFlashMessage();
                
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setPass(password_hash($passwordConfirm, PASSWORD_BCRYPT));
        $user->setUserRank('ROLE_MEMBER');

        $this->userRepo->create($user);
    }

    /*public function sendLink($form): void
    {
        ini_set('SMTP', 'smtp.bbox.fr');
        ini_set('sendmail_from', 'gerard.hubert@yahoo.fr');
        ini_set('smtp_port', '25');

        $to = 'mikado842@gmail.com';
        $subject = 'Confirmation de votre connexion à vore compte Multitap';
        $message = 'Le token est: ' . $form['hidden_input_token'] . '\r\n Bravo, vous vous êtes connecté avec succès';
        $headers = 'From: gerard.hubert@yahoo.fr';

        mail($to, $subject, $message, $headers);
    }*/

    public function logIn(array $logInForm): void
    {
        $user = $this->userRepo->findOneByUsername($logInForm['username']);
        switch ($user) {
            case null:
                $this->session->setFlashMessage('Nom d\'utilisateur inconnu et/ou mot de passe incorrect');
                header('Location: index.php?action=logInPage');
            break;
            case is_object($user):
                if (password_verify($logInForm['password'], $user->getPass()) === true) {
                    $this->session->deleteFlashMessage();
                    $this->session->setUserId($user->getUserId());
                    $this->session->setUsername($user->getUsername());
                    $this->session->setUserRank($user->getUserRank());
                    //$this->sendLink($logInForm);
                    header('Location: index.php?action=home');
                    exit;
                }
                    $this->session->setFlashMessage('Nom d\'utilisateur inconnu et/ou mot de passe incorrect');
                    header('Location: index.php?action=logInPage');
                ;
            break;
        }
    }
}
