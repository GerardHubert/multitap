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

    public function setUserId(int $userId): void
    {
        $_SESSION['userId'] = $userId;
    }

    public function getUserId(): ?int
    {
        return !empty($_SESSION['userId']) ? $_SESSION['userId'] : null;
    }

    public function setUsername(string $username): void
    {
        $_SESSION['username'] = $username;
    }

    public function getUsername(): ?string
    {
        return !empty($_SESSION['username']) ? $_SESSION['username'] : null;
    }

    public function setUserRank(string $rank): void
    {
        $_SESSION['userRank'] = $rank;
    }

    public function getUSerRank(): ?string
    {
        return !empty($_SESSION['userRank']) ? $_SESSION['userRank'] : null;
    }

    public function endSession(): void
    {
        session_unset();
        session_destroy();
    }
}
