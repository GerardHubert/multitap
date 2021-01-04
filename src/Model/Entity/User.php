<?php

declare(strict_types=1);

namespace App\Model\Entity;

final class User
{
    private $userId;
    private $username;
    private $email;
    private $pass;
    private $userRank;
    private $userDemand;
    private $isActive;
    private $token;
    private $signInDate;
    
    public function getUserId(): int
    {
        return (int) $this->userId;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;
        return $this;
    }

    public function getEmail(): string
    {
        return (string) $this->email;
    }

    public function setPass(string $password): self
    {
        $this->pass = $password;
        return $this;
    }

    public function getPass(): string
    {
        return (string) $this->pass;
    }

    public function setUserRank(string $userRank): self
    {
        $this->userRank = $userRank;
        return $this;
    }

    public function getUserRank(): string
    {
        return (string) $this->userRank;
    }

    public function setUserDemand(string $userDemand): self
    {
        $this->userDemand = $userDemand;
        return $this;
    }

    public function getUserDemand(): string
    {
        return (string) $this->userDemand;
    }

    public function setIsActive(string $accountState): self
    {
        $this->isActive = $accountState;
        return $this;
    }

    public function getIsActive(): string
    {
        return $this->isActive;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;
        return $this;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getSignInDate(): int
    {
        return (int) $this->signInDate;
    }

    public function setSignInDate(int $signInDate): self
    {
        $this->signInDate = $signInDate;
        return $this;
    }
}
