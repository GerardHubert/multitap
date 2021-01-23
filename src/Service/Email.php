<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Entity\User;
use App\View\View;

class Email
{
    private $view;
    private $from;
    private $version;
    private $content;

    public function __construct(View $view)
    {
        $this->view = $view;
        $this->from = 'FROM: gerard.hubert@yahoo.fr';
        $this->version = 'MIME-Version: 1.0';
        $this->content = "Content-type: text/html; charset=UTF-8";//charset=iso-8859-1\n";
    }

    public function sendInscriptionEmail(User $user): void
    {
        /**
         * Params : l'entité user
         */

        $to = $user->getEmail();
        $subject = 'Multitap : Confirmation d\'inscription';

        $message = $this->view->renderMail([
            'path' => 'frontoffice',
            'template' => 'inscriptionEmail',
            'data' => [
                'form' => $user
            ]
        ]);

        $headers = [$this->from, $this->version, $this->content];
        mail($to, $subject, $message, implode("\r\n", $headers));
    }

    public function sendResetPassLink(User $user): void
    {
        $to = $user->getEmail();
        $subject = 'Multitap : reinitialiser mon mot de passe';

        $message = $this->view->RenderMail([
            'path' => 'frontoffice',
            'template' => 'resetPassMail',
            'data' => [
                'user' => $user
            ]
        ]);

        $headers = [$this->from, $this->version, $this->content];
        mail($to, $subject, $message, implode("\r\n", $headers));
    }
}
