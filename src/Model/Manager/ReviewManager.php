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
        // renvoie les 6 dernières reviews
        
        return $this->reviewRepo->findByDate();
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

    public function showAll(): array
    {
        return $this->reviewRepo->findByAll();
    }

    public function showUserReviews(int $userId, int $status): array
    {
        return $this->reviewRepo->findByUserId($userId, $status);
    }

    public function addReview(array $reviewData): bool
    {
        $review = new Review();
        $review->setUserId((int) $reviewData['userId']);
        $review->setApiGameId((int) $reviewData['game_id']);
        $review->setGameTitle($reviewData['game_title']);
        $review->setReviewTitle($reviewData['review_title']);
        $review->setContent($reviewData['tinymceArea']);
        
        return $this->reviewRepo->create($review);
    }

    public function deleteReview(int $id): bool
    {
        $reviewToDelete = $this->reviewRepo->findById($id);
        return $this->reviewRepo->delete($reviewToDelete);
    }

    public function updateReview(array $newReview, int $reviewId): bool
    {
        $review = new Review();
        $review->setReviewTitle($newReview['review_title']);
        $review->setContent($newReview['review_modification']);

        return $this->reviewRepo->update($review, $reviewId);
    }
}
