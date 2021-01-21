<?php

declare (strict_types=1);

namespace App\Service\Security;

use App\Service\Http\Session;

class CheckActivity
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * classe pour vérifier le temps d'inactivité entre 2 requetes utilisateur
     * si le temps entre 2 requetes > 30 minutes (1800 secondes), on détruit les sessions
     */

    public function checkActivity(): void
    {  
        if ($this->session->getLastMove() !== null && time() > $this->session->getLastMove() + 1200) {
            $this->session->endSession();
        } elseif ($this->session->getLastMove() !== null && time() < $this->session->getLastMove() + 1200) {
            $this->session->setLastMove(time());
        }
    }
}