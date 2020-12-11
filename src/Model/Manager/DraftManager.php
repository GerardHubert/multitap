<?php

declare(strict_types=1);

namespace  App\Model\Manager;

use App\Model\Entity\Review;
use App\Model\Repository\ReviewRepository;
use App\Service\Security\Token;

class DraftManager
{
    private $reviewRepo;
    private $token;

    public function __construct(ReviewRepository $reviewRepo, Token $token)
    {
        $this->reviewRepo = $reviewRepo;
        $this->token = $token;
    }

    public function saveAsDraft(array $draftData): bool
    {
        $status = 1;
        $draft = new Review();
        $draft->setUserId((int) $draftData['userId']);
        $draft->setApiGameId((int) $draftData['game_id']);
        $draft->setGameTitle($draftData['game_title']);
        $draft->setReviewTitle($draftData['review_title']);
        $draft->setContent($draftData['tinymceArea']);
        $draft->setReviewStatus($status);

        return $this->reviewRepo->createDraft($draft);
    }

    public function showAllDrafts(int $draftStatus, int $userId): array
    {
        return $this->reviewRepo->findByStatus($draftStatus, $userId);
    }

    public function showOne($id): ?Review
    {
        return $this->reviewRepo->findById($id);
    }

    public function publishDraftAsReview(int $id): bool
    {
        //On récupère d'abord un objet Review selon l'id du brouillon qu'on veut publier
        $draft = $this->reviewRepo->findById((int) $id);
        
        //on passe la review + le nouveau status pour mise à jour par le repository
        $status = 0;
        return $this->reviewRepo->updateStatus($draft, (int) $status);
    }

    public function updateDraft(array $newDraft, int $id): bool
    {
        $draft = new Review();
        $draft->setReviewTitle($newDraft['draft_title']);
        $draft->setContent($newDraft['draft_modification']);

        return $this->reviewRepo->update($draft, $id);
    }

    public function deleteDraft(int $id): bool
    {
        $draftToDelete = $this->reviewRepo->findById($id);
        return $this->reviewRepo->delete($draftToDelete);
    }
}
