<?php

declare(strict_types=1);

namespace App\Service\Http;

class Session
{
    public function __construct()
    {
        session_start();
    }

    public function setToken(string $token): void
    {
        $_SESSION['token'] = $token;
    }

    public function getToken(): string
    {
        return $_SESSION['token'] ?? '';
    }

    public function setFlashMessage(string $message): void
    {
        $_SESSION['flashMessage'] = $message;
    }

    public function getFlashMessage(): string
    {
        return !empty($_SESSION['flashMessage']) ? $_SESSION['flashMessage'] : '';
    }

    public function deleteFlashMessage(): void
    {
        unset($_SESSION['flashMessage']);
    }

    public function endSession(): void
    {
        session_unset();
        session_destroy();
    }
}
