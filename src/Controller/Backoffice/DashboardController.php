<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\Model\Manager\CommentManager;
use App\Model\Manager\ReviewManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\AccessControl;
use App\Service\Security\Token;
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
    private $commentManager;
    private $token;
    private $session;
    private $accessControl;

    public function __construct(ReviewManager $reviewManager, View $view, Request $request, CommentManager $commentManager, Token $token, Session $session, AccessControl $accessControl)
    {
        $this->reviewManager = $reviewManager;
        $this->view = $view;
        $this->request = $request;
        $this->commentManager = $commentManager;
        $this->token = $token;
        $this->session = $session;
        $this->accessControl = $accessControl;
    }

    public function checkAccess(): void
    {
        if ($this->accessControl->isConnected() === false) {
            header('Location: index.php?action=logInPage');
            exit;
        }
    }

    /*public function displayAllAction(): void
    {
        $this->checkAccess();
        $reviews = $this->reviewManager->showAll();
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'allReviews',
            'data' => [
                'reviews' => $reviews
            ]
        ]);
    }*/

    public function displayUserReviewsAction(): void
    {
        $this->checkAccess();
        $status = 0;
        $reviews = $this->reviewManager->showUserReviews((int) $this->session->getUserId(), $status);
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
        $this->checkAccess();
        $this->token->setToken();
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'reviewEditor',
            'data' => []
        ]);
    }

    public function addReviewAction(): void
    {
        $this->checkAccess();

        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();
        
        switch ($inputToken === $sessionToken) {
            case true:
                $this->reviewManager->addReview($this->request->cleanPost());
                header('Location: index.php?action=dashboard');
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    public function deleteReviewAction(): void
    {
        $this->checkAccess();

        $this->reviewManager->deleteReview((int) $this->request->cleanGet()['id']);
        header('Location: index.php?action=dashboard');
        exit;
    }

    public function updateReviewPage(): void
    {
        $this->checkAccess();

        $this->token->setToken();
        $review = $this->reviewManager->showOne((int) $this->request->cleanGet()['id']);

        $this->view->render([
            'path' => 'backoffice',
            'template' => 'editReviewPage',
            'data' => [
                'review' => $review
            ]
        ]);
    }

    public function updateReviewAction(): void
    {
        $this->checkAccess();

        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $id = (int) $this->request->cleanGet()['id'];
                $update = $this->reviewManager->updateReview($this->request->cleanPost(), $id);
                switch ($update) {
                    case true:
                        header('Location: index.php?action=dashboard');
                    exit;
                    case false:
                        header("Location: index.php?action=update_review_page&id=$id");
                    break;
                }
                // no break
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    public function displayFlagCommentsAction(): void
    {
        $this->checkAccess();

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
        $this->checkAccess();

        $this->commentManager->deleteFromId((int) $this->request->cleanGet()['id']);
        
        header('Location: index.php?action=show_comments_to_moderate');
        exit;
    }

    public function authorizeCommentAction(): void
    {
        $this->checkAccess();

        $status = 2;
        $commentId = $this->request->cleanGet()['id'];
        $likes = $this->request->cleanGet()['likes'];
        $dislikes = $this->request->cleanGet()['dislikes'];
        $this->commentManager->authorizeComment((int) $commentId, $status, (int) $likes, (int) $dislikes);

        header('Location: index.php?action=show_comments_to_moderate');
        exit;
    }
}
