<?php

declare(strict_types=1);

namespace App\Model\Repository;

use \PDO;
use App\Model\Entity\Review;
use App\Service\Database;

final class ReviewRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findById(int $id): ?Review
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            INNER JOIN users
            ON users.userId = reviews.userId
            WHERE reviewId = :id");
        $request->bindParam(':id', $id);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();

        $review = $request->fetch();
        
        if ($review === null) {
            return null;
        }

        return $review;
    }

    public function findByDate(): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            INNER JOIN users
            ON users.userId = reviews.userId
            WHERE reviewStatus = 0
            ORDER BY reviewDate DESC
            LIMIT 6");
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        $reviews = $request->fetchAll();
        
        if ($reviews === null) {
            return null;
        }

        return $reviews;
    }

    public function findByOffset(int $offset, int $reviewsPerPage) : array
    {
        $request = $this->database->prepare("SELECT *, substring(content, 1, 300) AS content, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            INNER JOIN users
            ON users.userId = reviews.userId
            WHERE reviewStatus = 0
            ORDER BY reviewDate ASC
            LIMIT :reviewsPerPage OFFSET :offset");
        $request->bindParam(':offset', $offset, PDO::PARAM_INT);
        $request->bindParam(':reviewsPerPage', $reviewsPerPage, PDO::PARAM_INT);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        return $request->fetchAll();
    }

    public function findByAll(int $status): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            INNER JOIN users
            ON users.userId = reviews.userId
            WHERE reviewStatus = :reviewStatus
            ORDER BY reviewDate DESC");
        $request->bindParam(':reviewStatus', $status);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        $reviews = $request->fetchAll();
 
        if ($reviews === null) {
            return null;
        }

        return $reviews;
    }

    public function findByUserId(int $userId, $status): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            WHERE userId = :id AND reviewStatus = :reviewStatus
            ORDER BY reviewDate DESC");
        $request->bindParam(':id', $userId);
        $request->bindParam(':reviewStatus', $status);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        $reviews = $request->fetchAll();
        
        if ($reviews === null) {
            return null;
        }

        return $reviews;
    }

    public function findByGameId(int $gameId): array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            INNER JOIN users
            ON users.userId = reviews.userId
            WHERE apiGameId = :id");
        $request->bindParam(':id', $gameId);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();

        return $request->fetchAll();
        
    }

    public function findByEverything(): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            INNER JOIN users
            ON users.userId = reviews.userId
            ORDER BY reviewDate DESC");
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        $reviews = $request->fetchAll();
 
        if ($reviews === null) {
            return null;
        }

        return $reviews;
    }

    public function findEverythingByOffset(int $offset, int $reviewsPerPage): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            INNER JOIN users
            ON users.userId = reviews.userId
            ORDER BY reviewDate ASC
            LIMIT :reviewsPerPage OFFSET :offset");
        $request->bindParam(':offset', $offset, PDO::PARAM_INT);
        $request->bindParam(':reviewsPerPage', $reviewsPerPage, PDO::PARAM_INT);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        return $request->fetchAll();
    }

    public function create(Review $reviewData) : bool
    {
        $gameId = $reviewData->getApiGameId();
        $gameTitle = $reviewData->getGameTitle();
        $userId = $reviewData->getUserId();
        $reviewTitle = $reviewData->getReviewTitle();
        $content = $reviewData->getContent();
        $reviewStatus = $reviewData->getReviewStatus();

        $request = $this->database->prepare('INSERT INTO reviews (reviewTitle, gameTitle, apiGameId, content, userId, reviewDate, reviewStatus)
            VALUES (:reviewTitle, :gameTitle, :apiGameId, :content, :userId, NOW(), :reviewStatus)');

        $request->bindParam(':reviewTitle', $reviewTitle);
        $request->bindParam(':gameTitle', $gameTitle);
        $request->bindParam(':apiGameId', $gameId);
        $request->bindParam(':userId', $userId);
        $request->bindParam(':content', $content);
        $request->bindParam(':reviewStatus', $reviewStatus);
        
        return $request->execute();
    }

    public function update(Review $review, int $reviewId) : bool
    {
        $newTitle = $review->getReviewTitle();
        $newContent = $review->getContent();
        $request = $this->database->prepare("UPDATE reviews
            SET reviewTitle = :newReviewTitle, content = :newContent
            WHERE reviewId = :id");
        $request->bindParam(':newReviewTitle', $newTitle);
        $request->bindParam(':newContent', $newContent);
        $request->bindParam(':id', $reviewId);

        return $request->execute();
    }

    public function delete(Review $review) : bool
    {
        $id = $review->getReviewId();
        $request = $this->database->prepare('DELETE FROM reviews
            WHERE reviewId = :id');
        $request->bindParam(':id', $id);
        return $request->execute();
    }

    public function createDraft(Review $reviewData): bool
    {
        $gameId = $reviewData->getApiGameId();
        $gameTitle = $reviewData->getGameTitle();
        $userId = $reviewData->getUserId();
        $reviewTitle = $reviewData->getReviewTitle();
        $content = $reviewData->getContent();
        $status = $reviewData->getReviewStatus();

        $request = $this->database->prepare('INSERT INTO reviews (reviewTitle, gameTitle, apiGameId, content, userId, reviewDate, reviewStatus)
            VALUES (:reviewTitle, :gameTitle, :apiGameId, :content, :userId, NOW(), :reviewStatus)');

        $request->bindParam(':reviewTitle', $reviewTitle);
        $request->bindParam(':gameTitle', $gameTitle);
        $request->bindParam(':apiGameId', $gameId);
        $request->bindParam(':userId', $userId);
        $request->bindParam(':content', $content);
        $request->bindParam(':reviewStatus', $status);
        
        return $request->execute();
    }

    public function findByStatus(int $reviewStatus, int $userId): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s')
            FROM reviews
            WHERE reviewStatus = :reviewStatus AND userId = :userId
            ORDER BY reviewDate DESC");
        $request->bindParam(':reviewStatus', $reviewStatus);
        $request->bindParam(':userId', $userId);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        $reviews = $request->fetchAll();
        
        if ($reviews === null) {
            return null;
        }

        return $reviews;
    }
    
    public function updateStatus(Review $review, int $status): bool
    {
        $id = $review->getReviewId();
        $request = $this->database->prepare("UPDATE reviews
            SET reviewStatus = :newStatus, reviewDate = NOW()
            WHERE reviewId = :id");
        $request->bindParam(':id', $id);
        $request->bindParam(':newStatus', $status);

        return $request->execute();
    }

    public function updateToAnonymous(Review $review): bool
    {
        $id = $review->getReviewId();
        $anonymousUserId = $review->getUserId();
        $request = $this->database->prepare("UPDATE reviews
            SET userId = :anonymousUserId
            WHERE reviewId = :id");
        $request->bindParam(':id', $id);
        $request->bindParam('anonymousUserId', $anonymousUserId);

        return $request->execute();
    }

    public function updateToValidate(Review $review, int $reviewId): bool
    {
        $status = $review->getReviewStatus();
        $title = $review->getReviewTitle();
        $content = $review->getContent();
        $request = $this->database->prepare("UPDATE reviews
            SET reviewStatus = :newStatus, content = :newContent, reviewTitle = :newTitle, reviewDate = NOW()
            WHERE reviewId = :id");
        $request->bindParam(':newStatus', $status);
        $request->bindParam(':newContent', $content);
        $request->bindParam(':newTitle', $title);
        $request->bindParam(':id', $reviewId);

        return $request->execute();
    }
}
