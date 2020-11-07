<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Review;
use App\Model\Repository\CommentRepository;
use App\Model\Repository\ReviewRepository;
use App\Service\Security\Token;

final class ReviewManager
{
    private $reviewRepo;
    private $commentRepo;
    private $token;

    public function __construct(ReviewRepository $reviewRepository, CommentRepository $commentRepo, Token $token)
    {
        $this->reviewRepo = $reviewRepository;
        $this->commentRepo = $commentRepo;
        $this->token = $token;
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

    public function showTwo(int $page): array
    {
        $reviewsPerPage = 3;
        $pageToDisplay = $page - 1;
        $offset = $pageToDisplay * $reviewsPerPage;
        $totalReviews = count($this->reviewRepo->findByAll());
        $totalPages = ceil($totalReviews / $reviewsPerPage);
        return [$this->reviewRepo->findByOffset($offset), $totalPages];
    }
}
