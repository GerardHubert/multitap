<?php

declare(strict_types=1);

namespace App\Service\Security;

use App\Service\Http\Session;

class AccessControl
{
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function isConnected(): bool
    {
        if (!empty($this->session->getUsername()) && $this->session->getUsername() !== null) {
            return true;
        }
        return false;
    }

    public function getRole(): ?string
    {
        if (!empty($this->session->getUserRank()) && $this->session->getUserRank() !== null) {
            return $this->session->getUserRank();
        }
        return null;
    }

    public function getUsername(): ?string
    {
        if (!empty($this->session->getUsername()) && $this->session->getUsername() !== null) {
            return $this->session->getUsername();
        }
        return null;
    }
}
