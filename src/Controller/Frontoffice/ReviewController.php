<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Manager\CommentManager;
use App\Model\Manager\ReviewManager;
use App\Service\Http\Session;
use App\Service\Security\Token;
use App\View\View;

final class ReviewController
{
    private $reviewManager;
    private $view;
    private $commentManager;
    private $token;
    private $session;

    public function __construct(ReviewManager $reviewManager, View $view, CommentManager $commentManager, Token $token, Session $session)
    {
        $this->reviewManager = $reviewManager;
        $this->view = $view;
        $this->commentManager = $commentManager;
        $this->token = $token;
        $this->session = $session;
    }
    
    public function displayOneAction(int $id): void
    {
        $post = $this->reviewManager->showOne($id);
        $comments = $this->commentManager->showAllFromPost($id);
       
        if ($post !== null) {
            $this->token->setToken();
            $this->view->render(
                [
                'path' => 'frontoffice',
                'template' => 'review',
                'data' => [
                    'post' => $post,
                    'comments' => $comments,
                    ],
                ],
            );
        } elseif ($post === null) {
            header('Location: index.php?action=home');
            exit;
        }
    }

    public function displayHomeAction(): void
    {
        $posts = $this->reviewManager->showHome();

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'home',
            'data' => [
                'posts' => $posts
            ],
        ]);
    }

    public function displayAllAction(int $page): void
    {
        $reviews = $this->reviewManager->showTwo($page);

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'allReviews',
            'data' => [
                'reviews' => $reviews[0],
                'totalPages' => $reviews[1],
                'page' => $page
            ],
        ]);
    }
}
