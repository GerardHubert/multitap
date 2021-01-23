<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\Model\Manager\CommentManager;
use App\Model\Manager\ReviewManager;
use App\Model\Manager\UserManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\AccessControl;
use App\Service\Security\Token;
use App\View\View;
use App\Service\Security\CheckActivity;

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
    private $reviewsListToValidate;
    private $usersList;
    private $userManager;
    private $flagCommentsCount;

    public function __construct(ReviewManager $reviewManager, View $view, Request $request, CommentManager $commentManager, Token $token, Session $session, AccessControl $accessControl, UserManager $userManager)
    {
        $this->reviewManager = $reviewManager;
        $this->userManager = $userManager;
        $this->view = $view;
        $this->request = $request;
        $this->commentManager = $commentManager;
        $this->token = $token;
        $this->session = $session;
        $this->accessControl = $accessControl;
        $this->reviewsListToValidate = $this->reviewManager->showAllFromStatus(2);
        $this->usersList = $this->userManager->showAll();
        $this->flagCommentsCount = count($this->commentManager->showAllFromStatus(1));
    }

    public function checkAccess(): void
    {
        if ($this->accessControl->isConnected() === false || $this->accessControl->getRole() === 'ROLE_MEMBER') {
            header('Location: index.php?action=logInPage');
            exit;
        }
    }

    /**
     * Accueil du dashboard
     * le user voit ses reviews publiées
     */

    public function displayUserReviewsAction(): void
    {
        $this->checkAccess();
        $statusToShow = (int) $this->request->cleanGet()['status'];
        $reviews = $this->reviewManager->showUserReviews((int) $this->session->getUserId(), $statusToShow);
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'allReviews',
            'data' => [
                'reviews' => $reviews,
                'reviewsListTwo' => $this->reviewsListToValidate,
                'usersList' => $this->usersList,
                'flagCommentsCount' => $this->flagCommentsCount
            ]
        ]);
    }

    /**
     * Le user voit ses reviews soumises à validation
     * en attente de publication
     */

    public function showAwaitingReviewsAction(): void
    {
        $this->checkAccess();
        
        $statusToShow = (int) $this->request->cleanGet()['status'];
        $reviews = $this->reviewManager->showUserReviews((int) $this->session->getUserId(), $statusToShow);
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'awaitingReviews',
            'data' => [
                'reviews' => $reviews,
                'reviewsListTwo' => $this->reviewsListToValidate,
                'usersList' => $this->usersList,
                'flagCommentsCount' => $this->flagCommentsCount
            ]
        ]);
    }

    /**
     * methode menant à la page pour créer une nouvelle review
     */

    public function reviewEditor(): void
    {
        $this->checkAccess();
        $this->token->setToken();
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'reviewEditor',
            'data' => [
                'reviewsListTwo' => $this->reviewsListToValidate,
                'usersList' => $this->usersList,
                'flagCommentsCount' => $this->flagCommentsCount
            ]
        ]);
    }

    /**
     * Methode pour roles > reviewer
     * Affiche les reviews à valider, soumises par les reviewers
     */

    public function showReviewsToValidate(): void
    {
        $this->checkAccess();

        if ($this->session->getUserRank() === 'ROLE_REVIEWER' || $this->accessControl->getRole() === 'ROLE_MEMBER') {
            $this->session->setFlashMessage('Vous n\'êtes pas autorisé à accéder à cette page. Demandez à changer de rôle');
            header('Location: index.php?action=user_parameters_page');
            exit;
        }

        $status = 2;
        $reviews = $this->reviewManager->showAllFromStatus($status);

        $this->view->render([
            'path' => 'backoffice',
            'template' => 'reviewsToAuthorize',
            'data' => [
                'reviews' => $reviews,
                'reviewsListTwo' => $this->reviewsListToValidate,
                'usersList' => $this->usersList,
                'flagCommentsCount' => $this->flagCommentsCount
            ]
        ]);
    }

    /**
     * Validation d'une review
     */

    public function validateReviewAction(): void
    {
        $this->checkAccess();

        switch ($this->request->cleanPost()['hidden_input_token'] === $this->session->getToken()) {
            case true:
                if ($this->session->getUserRank() === 'ROLE_REVIEWER') {
                    $status = 2;
                } else {
                    $status = 0;
                }
        
                $this->reviewManager->validateReview($status, (int) $this->request->cleanGet()['id'], $this->request->cleanPost());
        
                if ($this->session->getUserRank() === 'ROLE_REVIEWER') {
                    header('Location: index.php?action=show_waiting_reviews&status=2');
                    exit;
                } elseif ($this->session->getUserRank() === 'ROLE_ADMIN' || $this->session->getUserRank() === 'ROLE_EDITOR') {
                    header('Location: index.php?action=show_reviews_to_validate');
                    exit;
                }
            break;
            case false:
                $this->session->endSession();
                header('Location: index.php?action=logInPage');
            break;
        }
    }

    /**
     * Soumettre une review à la validation depuis la page d'édition
     */

    public function submitReviewAction(): void
    {
        $this->checkAccess();

        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $this->reviewManager->submitReview($this->request->cleanPost());
                header('Location: index.php?action=show_waiting_reviews&status=2');
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    /**
     * Soumettre les modifications apportées à une review déjà publiée
     */

    public function submitUpdateAction(): void
    {
        $this->checkAccess();
        
        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $this->reviewManager->submitUpdate($this->request->cleanPost(), (int) $this->request->cleanGet()['id']);
                header('Location: index.php?action=show_waiting_reviews&status=2');
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    /**
     * Ajouter création d'une review
     */

    public function addReviewAction(): void
    {
        $this->checkAccess();

        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();
        
        switch ($inputToken === $sessionToken) {
            case true:
                $this->reviewManager->addReview($this->request->cleanPost());
                header('Location: index.php?action=dashboard&status=0');
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
        header('Location: index.php?action=dashboard&status=0');
        exit;
    }

    /**
     * Page modification d'une review
     */

    public function updateReviewPage(): void
    {
        $this->checkAccess();

        $this->token->setToken();
        $review = $this->reviewManager->showOne((int) $this->request->cleanGet()['id']);

        $this->view->render([
            'path' => 'backoffice',
            'template' => 'editReviewPage',
            'data' => [
                'review' => $review,
                'reviewsListTwo' => $this->reviewsListToValidate,
                'usersList' => $this->usersList,
                'flagCommentsCount' => $this->flagCommentsCount
            ]
        ]);
    }

    /**
     * Enregistrement des modifications
     */

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
                        header('Location: index.php?action=dashboard&status=0');
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

        if ($this->accessControl->getRole() !== 'ROLE_ADMIN' && $this->accessControl->getRole() !== 'ROLE_EDITOR') {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

        $commentStatus = 1;
        $flagComments = $this->commentManager->showAllFromStatus($commentStatus);
        
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'commentsModeration',
            'data' => [
                'flagComments' => $flagComments,
                'reviewsListTwo' => $this->reviewsListToValidate,
                'usersList' => $this->usersList,
                'flagCommentsCount' => $this->flagCommentsCount
            ]
        ]);
    }

    public function deleteCommentAction(): void
    {
        $this->checkAccess();

        if ($this->accessControl->getRole() !== 'ROLE_ADMIN' && $this->accessControl->getRole() !== 'ROLE_EDITOR') {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }
        $commentToDelete = $this->commentManager->showOneFromId((int) $this->request->cleanGet()['id']);
        $this->commentManager->deleteFromId($commentToDelete);
        
        header('Location: index.php?action=show_comments_to_moderate');
        exit;
    }

    public function authorizeCommentAction(): void
    {
        $this->checkAccess();

        if ($this->accessControl->getRole() !== 'ROLE_ADMIN' && $this->accessControl->getRole() !== 'ROLE_EDITOR') {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }

        $status = 2;
        $commentId = $this->request->cleanGet()['id'];
        $likes = $this->request->cleanGet()['likes'];
        $dislikes = $this->request->cleanGet()['dislikes'];
        $this->commentManager->authorizeComment((int) $commentId, $status, (int) $likes, (int) $dislikes);

        header('Location: index.php?action=show_comments_to_moderate');
        exit;
    }

    public function showTotalReviews(): void
    {
        $this->checkAccess();
        $page = $this->request->cleanGet()['page'];

        if ($this->accessControl->getRole() !== 'ROLE_ADMIN') {
            $this->session->endSession();
            header('Location: index.php?action=logInPage');
            exit;
        }
        $reviews = $this->reviewManager->showEverything((int) $page);

        $this->view->render([
            'path' => 'backoffice',
            'template' => 'allReviewsAllUsersAllStatus',
            'data' => [
                'reviews' => $reviews[0],
                'totalPages' => $reviews[1],
                'page' => $page,
                'reviewsListTwo' => $this->reviewsListToValidate,
                'usersList' => $this->usersList,
                'flagCommentsCount' => $this->flagCommentsCount
            ]
        ]);
    }
}
