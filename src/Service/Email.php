<?php

declare(strict_types=1);

namespace App\Service;

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
        $this->content = "Content-type: text/html; charset=iso-8859-1\n";
    }

    public function sendInscriptionEmail(array $signInForm): void
    {
        /**
         * Params : le formulaire d'inscription
         */

        $to = $signInForm['email'];
        $subject = 'Multitap : Confirmation d\'inscription';

        $message = $this->view->renderMail([
            'path' => 'frontoffice',
            'template' => 'inscriptionEmail',
            'data' => [
                'form' => $signInForm
            ]
        ]);

        $headers = [$this->from, $this->version, $this->content];
        mail($to, $subject, $message, implode("\r\n", $headers));
    }
}
