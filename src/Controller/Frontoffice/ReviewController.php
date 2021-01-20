<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Manager\CommentManager;
use App\Model\Manager\ReviewManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\Token;
use App\View\View;
use App\Service\Security\CheckActivity;

final class ReviewController
{
    private $reviewManager;
    private $view;
    private $commentManager;
    private $token;
    private $session;
    private $request;
    private $checkActivity;

    public function __construct(ReviewManager $reviewManager, View $view, CommentManager $commentManager, Token $token, Session $session, Request $request, CheckActivity $checkActivity)
    {
        $this->reviewManager = $reviewManager;
        $this->view = $view;
        $this->commentManager = $commentManager;
        $this->token = $token;
        $this->session = $session;
        $this->request = $request;
        $this->checkActivity = $checkActivity;

        $this->checkActivity->checkActivity();
    }
    
    public function displayOneAction(): void
    {
        $post = $this->reviewManager->showOne((int) $this->request->cleanGet()['id']);
        $comments = $this->commentManager->showAllFromPost((int) $this->request->cleanGet()['id']);
        
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
            ]
        ]);
    }

    public function displayAllAction(): void
    {
        $page = (int) $this->request->cleanGet()['page'];
        $reviews = $this->reviewManager->showFive($page);

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

    public function showUserReviewsAction(): void
    {
        $username = $this->request->cleanGet()['username'];
        $status = 0;
        $reviews = $this->reviewManager->showUserReviews((int) $this->request->CleanGet()['id'], $status);

        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'reviewsOfUser',
            'data' => [
                'reviews' => $reviews,
                'username' => $username
            ],
        ]);
    }

    public function oneGameReviewsAction(): void
    {
        $gameId = $this->request->cleanGet()['id'];
        $reviews = $this->reviewManager->showAllFromGameId((int) $gameId);
        $gameTitle = $this->request->cleanGet()['title'];
        
        $this->view->render([
            'path' => 'frontoffice',
            'template' => 'reviewsOfOneGame',
            'data' => [
                'reviews' => $reviews,
                'gameTitle' => $gameTitle
            ],
        ]);
    }
    
}
