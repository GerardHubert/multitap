<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\Model\Manager\ReviewManager;
use App\Service\Http\Request;
use App\View\View;

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

    public function __construct(ReviewManager $reviewManager, View $view, Request $request)
    {
        $this->reviewManager = $reviewManager;
        $this->view = $view;
        $this->request = $request;
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

    public function editReviewPage(int $reviewId): void
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
}
