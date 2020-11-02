<?php

declare(strict_types=1);

namespace App\Model\Repository;

use \PDO;
use App\Model\Entity\Review;
use App\Service\Database;

final class ReviewRepository
{
    private $database;
    private $path;
    private $review;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findById(int $id): ?Review
    {
        $request = $this->database->prepare('SELECT * 
            FROM reviews
            WHERE id = :id');
        $request->bindParam(':id', $id);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();

        $review = $request->fetch();
        
        if ($review === null) {
            return null;
        }

        return $review;
    }

    public function findByAll(): ?array
    {
        $request = $this->database->prepare('SELECT * 
            FROM reviews');
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
        $request = $this->database->prepare("SELECT *, substring(content, 1, 300) AS content
            FROM reviews
            LIMIT 3 OFFSET :offset");
        $request->bindParam(':offset', $offset, PDO::PARAM_INT);
        $request->setFetchMode(PDO::FETCH_CLASS, Review::class);
        $request->execute();
        return $request->fetchAll();
    }

    public function create(Review $post) : bool
    {
        return false;
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
