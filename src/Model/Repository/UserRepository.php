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
        $isActive = $user->getIsActive();
        $token = $user->getToken();
        $signInDate = $user->getSignInDate();

        $request = $this->database->prepare('INSERT INTO users (username, email, pass, userRank, isActive, token, signInDate)
            VALUES (:username, :email, :pass, :user_rank, :isActive, :token, :signInDate)');
        $request->bindParam(':username', $username);
        $request->bindParam(':email', $email);
        $request->bindParam(':pass', $password);
        $request->bindParam(':user_rank', $rank);
        $request->bindParam(':isActive', $isActive);
        $request->bindParam(':token', $token);
        $request->bindParam(':signInDate', $signInDate);

        return $request->execute();
    }

    public function updateToken(User $user): bool
    {
        $id = $user->getUserId();
        $token = $user->getToken();
        $newDate = $user->getSignInDate();

        $request = $this->database->prepare('UPDATE users
            SET token = :token, signInDate = :newDate
            WHERE userId = :id');
        $request->bindParam(':id', $id);
        $request->bindParam(':token', $token);
        $request->bindParam(':newDate', $newDate);

        return $request->execute();
    }

    public function updateIsActive(User $newUser): bool
    {
        $isActive = $newUser->getIsActive();
        $userId = $newUser->getUserId();
        $token = $newUser->getToken();
        $signInDate = $newUser->getSignInDate();

        $request = $this->database->prepare('UPDATE users
            SET isActive = :isActive, token = :token, signInDate = :signInDate
            WHERE userId = :id');
        $request->bindParam(':isActive', $isActive);
        $request->bindParam(':id', $userId);
        $request->bindParam(':token', $token);
        $request->bindParam(':signInDate', $signInDate);

        return $request->execute();
    }

    public function findById(int $id): ?User
    {
        $request = $this->database->prepare('SELECT *
            FROM users
            WHERE userId = :id');
        $request->bindParam(':id', $id);
        $request->setFetchMode(PDO::FETCH_CLASS, User::class);
        $request->execute();

        $user = $request->fetch();

        return $user === false ? null : $user;
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

    public function findOneByEmail(string $email): ?User
    {
        $request = $this->database->prepare('SELECT *
            FROM users
            WHERE email = :email');
        $request->bindParam(':email', $email);
        $request->setFetchMode(PDO::FETCH_CLASS, User::class);
        $request->execute();

        $user = $request->fetch();

        return $user === false ? null : $user;
    }

    public function findByAll(): ?array
    {
        $request = $this->database->prepare("SELECT *
            FROM users
            ORDER BY username");
        $request->setFetchMode(PDO::FETCH_CLASS, User::class);
        $request->execute();
        $users = $request->fetchAll();
        
        if ($users === false) {
            return null;
        }

        return $users;
    }

    public function updateIsActiveByAdmin(User $user): bool
    {
        $id = $user->getUserId();
        $isActive = $user->getIsActive();

        $request = $this->database->prepare('UPDATE users
            SET isActive = :isActive
            WHERE userId = :id');
        $request->bindParam(':id', $id);
        $request->bindParam(':isActive', $isActive);
        
        return $request->execute();
    }

    public function delete(User $userToDelete): bool
    {
        $userId = $userToDelete->getUSerId();
        $request = $this->database->prepare('DELETE FROM users
            WHERE userId = :id');
        $request->bindParam(':id', $userId);
        
        return $request->execute();
    }

    public function updateUsernameFromUser(User $user): bool
    {
        $userId = $user->getUserId();
        $newUsername = $user->getUsername();

        $request =$this->database->prepare("UPDATE users
            SET username = :newUsername
            WHERE userId = :userId");
        $request->bindParam(':userId', $userId);
        $request->bindParam(':newUsername', $newUsername);

        return $request->execute();
    }

    public function updateEmailFromUser(User $user): bool
    {
        $userId = $user->getUserId();
        $newEmail = $user->getEmail();

        $request =$this->database->prepare("UPDATE users
            SET email = :newEmail
            WHERE userId = :userId");
        $request->bindParam(':userId', $userId);
        $request->bindParam(':newEmail', $newEmail);

        return $request->execute();
    }

    public function updatePassFromUser(User $user): bool
    {
        $userId = $user->getUserId();
        $newPass = $user->getPass();
        $resetDate = $user->getSigninDate();
        $resetToken = $user->getToken();

        $request =$this->database->prepare("UPDATE users
            SET pass = :newPass, signinDate = :noDate, token = :noToken
            WHERE userId = :userId");
        $request->bindParam(':userId', $userId);
        $request->bindParam(':newPass', $newPass);
        $request->bindParam(':noToken', $resetToken);
        $request->bindParam('noDate', $resetDate);

        return $request->execute();
    }

    public function updateRankRequest(User $user): bool
    {
        $userDemand = $user->getUserDemand();
        $previousRank = $user->getPreviousRank();
        
        $id = $user->getUserId();

        $request = $this->database->prepare('UPDATE users
            SET userDemand = :userDemand, previousRank = :previousRank
            WHERE userId = :userId');
        $request->bindParam(':userDemand', $userDemand);
        $request->bindParam(':previousRank', $previousRank);
        $request->bindParam(':userId', $id);

        return $request->execute();
    }

    public function updateRankFromUser(User $user): ?bool
    {
        $id = $user->getUserId();
        $newRank = $user->getUserRank();
        $resetDemand = $user->getUserDemand();

        $request = $this->database->prepare('UPDATE users
            SET userRank = :newRank, userDemand = :resetDemand
            WHERE userId = :userId');
        $request->bindParam(':newRank', $newRank);
        $request->bindParam(':resetDemand', $resetDemand);
        $request->bindParam(':userId', $id);

        return $request->execute();
    }
}
