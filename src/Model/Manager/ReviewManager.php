<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Review;
use App\Model\Entity\User;
use App\Model\Repository\CommentRepository;
use App\Model\Repository\ReviewRepository;
use App\Service\Security\Token;
use PhpCsFixer\FixerDefinition\VersionSpecificCodeSample;

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
        
        $reviews = $this->reviewRepo->findByDate();
        return $reviews;

    }

    public function showOne(int $id): ?Review
    {
        return $this->reviewRepo->findById($id);
    }

    public function showFive(int $page): array
    {
        $status = 0;
        $reviewsPerPage = 5;
        $pageToDisplay = $page - 1;
        $offset = $pageToDisplay * $reviewsPerPage;
        $totalReviews = count($this->reviewRepo->findByAll($status));
        $totalPages = ceil($totalReviews / $reviewsPerPage);
        return [$this->reviewRepo->findByOffset($offset), $totalPages];
    }

    public function showAll(): array
    {
        $status = 0;
        return $this->reviewRepo->findByAll($status);
    }

    public function showUserReviews(int $userId, int $status): array
    {
        return $this->reviewRepo->findByUserId($userId, $status);
    }

    public function submitReview(array $reviewData): bool
    {
        $review = new Review();
        $review->setUserId((int) $reviewData['userId']);
        $review->setApiGameId((int) $reviewData['game_id']);
        $review->setGameTitle($reviewData['game_title']);
        $review->setReviewTitle($reviewData['review_title']);
        $review->setContent($reviewData['tinymceArea']);
        $review->setReviewStatus(2);
        
        return $this->reviewRepo->create($review);
    }

    public function submitUpdate(array $updateForm, int $reviewId): bool
    {
        $review = new Review();
        $review->setReviewTitle($updateForm['review_title']);
        $review->setContent($updateForm['review_modification']);
        $review->setReviewStatus(2);

        return $this->reviewRepo->updateToValidate($review, $reviewId);
    }

    public function addReview(array $reviewData): bool
    {
        $review = new Review();
        $review->setUserId((int) $reviewData['userId']);
        $review->setApiGameId((int) $reviewData['game_id']);
        $review->setGameTitle($reviewData['game_title']);
        $review->setReviewTitle($reviewData['review_title']);
        $review->setContent($reviewData['tinymceArea']);
        $review->setReviewStatus(0);
        
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

    public function setToAnonymousAction(array $reviews, User $anonymousUser): void
    {
        foreach ($reviews as $review) {
            $review->setUserId((int) $anonymousUser->getUserId());
            $this->reviewRepo->UpdateToAnonymous($review);
        }
    }

    public function showAllFromStatus(int $reviewStatus): ?array
    {
        return $this->reviewRepo->findByAll($reviewStatus);
    }

    public function validateReview(int $status, int $reviewId, array $reviewData): bool
    {
        $review = new Review();
        $review->setReviewTitle($reviewData['review_title']);
        $review->setContent($reviewData['review_modification']);
        $review->setReviewStatus($status);

        return $this->reviewRepo->updateToValidate($review, $reviewId);
    }

    public function showEverything(): ?array
    {
        return $this->reviewRepo->findByEverything();
    }
}
