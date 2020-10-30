<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Review;
use App\Model\Repository\{ReviewRepository, CommentRepository};

final class ReviewManager
{
    private $reviewRepo;
    private $commentRepo;

    public function __construct(ReviewRepository $reviewRepository, CommentRepository $commentRepo)
    {
        $this->reviewRepo = $reviewRepository;
        $this->commentRepo = $commentRepo;
    }

    public function showHome(): ?array
    {
        // renvoie tous les posts + le nombre de commentaires par post

        return $this->reviewRepo->findByAll();
    }

    public function showOne(int $id): ?Review
    {
        return $this->reviewRepo->findById($id);
    }

    public function showTen(): array
    {
        return $this->reviewRepo->findByOffset();
    }
}
