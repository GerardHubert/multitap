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
        $content = htmlSpecialChars_decode($reviewData->getContent());

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

    public function update(Review $post) : bool
    {
        return false;
    }

    public function delete(Review $post) : bool
    {
        return false;
    }
}
