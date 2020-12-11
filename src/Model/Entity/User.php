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

    public function getUSerRank(): string
    {
        return (string) $this->userRank;
    }
}
