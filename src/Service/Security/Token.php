<?php
declare(strict_types=1);

namespace App\Service\Security;

use App\Service\Http\Session;

final class Token
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function setToken() : string
    {
        //On génère une chaine de 50 caracctères aléatoire
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyzéèçà^êëâäùûüôöñ';
        $string = '';
        $stringLength = 49;

        for ($i = 0; $i <= $stringLength; $i++) {
            $string = $string.$characters[random_int(0, mb_strlen($characters) - 1)];
        }

        //On hashe la chaine pour obtenir le token
        $token = password_hash($string, PASSWORD_BCRYPT);
        $this->session->setToken($token);
        return $token;
    }
}
