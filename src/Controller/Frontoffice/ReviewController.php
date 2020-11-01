<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Manager\CommentManager;
use App\Model\Manager\ReviewManager;
use App\View\View;

final class ReviewController
{
    private $reviewManager;
    private $view;
    private $commentManager;

    public function __construct(ReviewManager $reviewManager, View $view, CommentManager $commentManager)
    {
        $this->reviewManager = $reviewManager;
        $this->view = $view;
        $this->commentManager = $commentManager;
    }
    
    public function displayOneAction(int $id): void
    {
        $post = $this->reviewManager->showOne($id);
        $comments = $this->commentManager->showAllFromPost($id);
        if ($post !== null) {
            $this->view->render(
                [
                'template' => 'review',
                'data' => [
                    'post' => $post,
                    'comments' => $comments,
                    ],
                ],
            );
        } elseif ($post === null) {
            echo "Erreur, ce post n'existe pas. Faire une redirection vers la page accueil";
        }
    }

    public function displayHomeAction(): void
    {
        $posts = $this->reviewManager->showHome();

        $this->view->render([
            'template' => 'home',
            'data' => ['posts' => $posts],
        ]);

    }

    public function displayAllAction(int $page): void
    {
        $reviews = $this->reviewManager->showTwo($page);
        $this->view->render([
            'template' => 'allReviews',
            'data' => [
                'reviews' => $reviews[0],
                'totalPages' => $reviews[1],
                'page' => $page
            ],
        ]);
    }
}
