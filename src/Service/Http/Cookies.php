<?php
declare(strict_types=1);

namespace App\Service\Http;

class Cookies
{
    public function setRole(string $role): void
    {
        setCookie('role', $role, time()+3600*24);
    }

    public function getRole(): ?string
    {
        return !empty($_COOKIE['role']) ? $_COOKIE['role'] : null;
    }
}
