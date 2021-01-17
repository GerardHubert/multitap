<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Manager\CommentManager;
use App\Model\Manager\ReviewManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\Token;
use App\View\View;
use \DateTime;
use \DateTimeZone;
use \DateInterval;

final class ReviewController
{
    private $reviewManager;
    private $view;
    private $commentManager;
    private $token;
    private $session;
    private $request;

    public function __construct(ReviewManager $reviewManager, View $view, CommentManager $commentManager, Token $token, Session $session, Request $request)
    {
        $this->reviewManager = $reviewManager;
        $this->view = $view;
        $this->commentManager = $commentManager;
        $this->token = $token;
        $this->session = $session;
        $this->request = $request;
        $this->checkActivity();
    }

    public function checkActivity(): void
    {
        /**
         * fonction pour vérifier quand a été faite la derniere requete de l'utilisateur
         * si la dernière requete a lieu un certain laps de temps après la précédente, 
         * on considère la session expirée, et on la détruit
         */

        if ($this->session->getLastMove() !== null && time() > $this->session->getLastMove() + 1800) {
                $this->session->endSession();
                header('Location: http://localhost:8000'.$_SERVER['REQUEST_URI']);
                exit;
        } elseif ($this->session->getLastMove() !== null && time() < $this->session->getLastMove() + 1800) {
            $this->session->setLastMove(time());
        }
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
                'posts' => $posts,
            ],
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
