<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Review;
use App\Model\Repository\ReviewRepository;
use App\Service\Security\Token;

final class DraftManager
{
    private $reviewRepo;
    private $token;

    public function __construct(ReviewRepository $reviewRepository, Token $token)
    {
        $this->reviewRepo = $reviewRepository;
        $this->token = $token;
    }

    public function saveAsDraft(array $draftData): bool
    {
        $status = 1;
        $draft = new Review();
        $draft->setReviewer($draftData['reviewer']);
        $draft->setApiGameId((int) $draftData['game_id']);
        $draft->setGameTitle($draftData['game_title']);
        $draft->setReviewTitle($draftData['review_title']);
        $draft->setContent($draftData['tinymceArea']);
        $draft->setReviewStatus($status);

        return $this->reviewRepo->createDraft($draft);
    }

    public function showAll(): array
    {
        $draftStatus = 1;
        return $this->reviewRepo->findByStatus($draftStatus);
    }
}
