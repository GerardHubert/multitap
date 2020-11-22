<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\Model\Manager\ReviewManager;
use App\Service\Http\Request;
use App\View\View;
use App\Model\Manager\CommentManager;

/**
 * Cette classe va permettre de vérifier le rang de la personne connecté pour afficher:
 * toutes les reviews, tous les commentaires et les commentaires à modérer si rang administrateur (0);
 * les reviews à corriger avant publication si rang rédacteur/correcteur (1);
 * uniquement mes reviews si rang reviewer (2)
 *
 * Tous les rangs ont mes reviews et brouillons
 */


class DashboardController
{
    private $reviewManager;
    private $view;
    private $request;
    private $commentManager;

    public function __construct(ReviewManager $reviewManager, View $view, Request $request, CommentManager $commentManager)
    {
        $this->reviewManager = $reviewManager;
        $this->view = $view;
        $this->request = $request;
        $this->commentManager = $commentManager;
    }

    public function displayAllAction(): void
    {
        $reviews = $this->reviewManager->showAll();
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'allReviews',
            'data' => [
                'reviews' => $reviews
            ]
        ]);
    }

    public function reviewEditor(): void
    {
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'reviewEditor',
            'data' => []
        ]);
    }

    public function addReviewAction(array $review): void
    {
        $this->reviewManager->addReview($review);
        header('Location: index.php?action=dashboard');
        exit;
    }

    public function deleteReviewAction(int $reviewId): void
    {
        $this->reviewManager->deleteReview($reviewId);
        header('Location: index.php?action=dashboard');
        exit;
    }

    public function updateReviewPage(int $reviewId): void
    {
        $review = $this->reviewManager->showOne($reviewId);

        $this->view->render([
            'path' => 'backoffice',
            'template' => 'editReviewPage',
            'data' => [
                'review' => $review
            ]
        ]);
    }

    public function updateReviewAction(array $editedReview, int $reviewId): void
    {
        $update = $this->reviewManager->updateReview($editedReview, $reviewId);
        switch ($update) {
            case true:
                header('Location: index.php?action=dashboard');
            exit;
            case false:
                header("Location: index.php?action=update_review_page&id=$reviewId");
            exit;
        }
    }

    public function saveDraftAction(): void
    {
        $this->reviewManager->saveAsDraft($this->request->cleanPost());

        header('Location: index.php?action=dashboard');
        exit;
    }

    public function displayDraftsAction(): void
    {
        $draftStatus = 1;
        $drafts = $this->reviewManager->showAllDrafts($draftStatus);
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'drafts',
            'data' => [
                'drafts' => $drafts
            ]
        ]);
    }

    public function displayFlagCommentsAction(): void
    {
        $commentStatus = 1;
        $flagComments = $this->commentManager->showAllFromStatus($commentStatus);
        
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'commentsModeration',
            'data' => [
                'flagComments' => $flagComments
            ]
        ]);
    }

    public function deleteCommentAction(): void
    {
        $this->commentManager->deleteFromId((int) $this->request->cleanGet()['id']);
        
        header('Location: index.php?action=show_comments_to_moderate');
        exit;
    }
}
