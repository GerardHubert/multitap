<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Review;
use App\Service\Database;
use \PDO;

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

    public function findByOffset() : array
    {
        $offset = 1;
        $request = $this->database->prepare('SELECT *
            FROM reviews
            ORDER BY reviews.date');
        $request->execute();
        return $request->fetchAll(\PDO::FETCH_ASSOC);
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
