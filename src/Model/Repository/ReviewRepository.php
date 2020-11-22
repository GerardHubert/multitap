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
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s') AS reviewDate
            FROM reviews
            WHERE id = :id");
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
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s') AS reviewDate
            FROM reviews
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

    public function findByOffset(int $offset) : array
    {
        $request = $this->database->prepare("SELECT *, substring(content, 1, 300) AS content, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s') AS reviewDate
            FROM reviews
            WHERE reviewStatus = 0
            ORDER BY reviewDate ASC
            LIMIT 3 OFFSET :offset");
        $request->bindParam(':offset', $offset, PDO::PARAM_INT);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        return $request->fetchAll();
    }

    public function findByAll(): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s') AS reviewDate
            FROM reviews
            WHERE reviewStatus = 0
            ORDER BY reviewDate DESC");
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        $reviews = $request->fetchAll();
        
        if ($reviews === null) {
            return null;
        }

        return $reviews;
    }

    public function create(Review $reviewData) : bool
    {
        $gameId = $reviewData->getApiGameId();
        $gameTitle = $reviewData->getGameTitle();
        $reviewer = $reviewData->getReviewer();
        $reviewTitle = $reviewData->getReviewTitle();
        $content = $reviewData->getContent();

        $request = $this->database->prepare('INSERT INTO reviews (reviewTitle, gameTitle, apiGameId, content, reviewer, reviewDate)
            VALUES (:reviewTitle, :gameTitle, :apiGameId, :content, :reviewer, NOW())');

        $request->bindParam(':reviewTitle', $reviewTitle);
        $request->bindParam(':gameTitle', $gameTitle);
        $request->bindParam(':apiGameId', $gameId);
        $request->bindParam(':reviewer', $reviewer);
        $request->bindParam(':content', $content);
        
        $creation = $request->execute();
        return $creation;
    }

    public function update(Review $review, int $reviewId) : bool
    {
        $newTitle = $review->getReviewTitle();
        $newContent = $review->getContent();
        $request = $this->database->prepare('UPDATE reviews
            SET reviewTitle = :newReviewTitle, content = :newContent
            WHERE id = :id');
        $request->bindParam(':newReviewTitle', $newTitle);
        $request->bindParam(':newContent', $newContent);
        $request->bindParam(':id', $reviewId);

        return $request->execute();
    }

    public function delete(Review $review) : bool
    {
        $id = $review->getId();
        $request = $this->database->prepare('DELETE FROM reviews
            WHERE id = :id');
        $request->bindParam(':id', $id);
        return $request->execute();
    }

    public function createDraft(Review $reviewData): bool
    {
        $gameId = $reviewData->getApiGameId();
        $gameTitle = $reviewData->getGameTitle();
        $reviewer = $reviewData->getReviewer();
        $reviewTitle = $reviewData->getReviewTitle();
        $content = $reviewData->getContent();
        $status = $reviewData->getReviewStatus();

        $request = $this->database->prepare('INSERT INTO reviews (reviewTitle, gameTitle, apiGameId, content, reviewer, reviewDate, reviewStatus)
            VALUES (:reviewTitle, :gameTitle, :apiGameId, :content, :reviewer, NOW(), :reviewStatus)');

        $request->bindParam(':reviewTitle', $reviewTitle);
        $request->bindParam(':gameTitle', $gameTitle);
        $request->bindParam(':apiGameId', $gameId);
        $request->bindParam(':reviewer', $reviewer);
        $request->bindParam(':content', $content);
        $request->bindParam(':reviewStatus', $status);
        
        $creation = $request->execute();
        return $creation;
    }

    public function findByStatus(int $reviewStatus): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(reviewDate, '%d/%m/%Y - %H:%i:%s') AS reviewDate
            FROM reviews
            WHERE reviewStatus = :reviewStatus
            ORDER BY reviewDate DESC");
        $request->bindParam(':reviewStatus', $reviewStatus);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        $reviews = $request->fetchAll();
        
        if ($reviews === null) {
            return null;
        }

        return $reviews;
    }
}
