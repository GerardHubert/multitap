<?php

declare(strict_types=1);

namespace App\Model\Repository;

use \PDO;
use App\Model\Entity\User;
use App\Service\Database;

final class UserRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function create(User $user): bool
    {
        $username = $user->getUsername();
        $email = $user->getEmail();
        $password = $user->getPass();
        $rank = $user->getUserRank();

        $request = $this->database->prepare('INSERT INTO users (username, email, pass, userRank)
            VALUES (:username, :email, :pass, :user_rank)');
        $request->bindParam(':username', $username);
        $request->bindParam(':email', $email);
        $request->bindParam(':pass', $password);
        $request->bindParam(':user_rank', $rank);

        return $request->execute();
    }

    public function findOneByUsername(string $username): ?User
    {
        $request = $this->database->prepare('SELECT *
            FROM users
            WHERE username = :username');
        $request->bindParam(':username', $username);
        $request->setFetchMode(PDO::FETCH_CLASS, User::class);
        $request->execute();
    
        $user = $request->fetch();
        if ($user === false) {
            return null;
        }

        return $user;
    }
}
