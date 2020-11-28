<?php

declare(strict_types=1);

namespace  App\Service;

use App\Controller\Backoffice\DashboardController;
use App\Controller\Frontoffice\CommentController;
use App\Controller\Frontoffice\ReviewController;
use App\Model\Manager\CommentManager;
use App\Model\Manager\ReviewManager;
use App\Model\Repository\CommentRepository;
use App\Model\Repository\ReviewRepository;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\Token;
use App\View\View;

final class Router
{
    private $database;
    private $view;
    private $get;
    private $reviewRepo;
    private $commentRepo;
    private $reviewManager;
    private $commentManager;
    private $reviewController;
    private $commentController;
    private $post;
    private $session;
    private $token;
    private $request;
    private $dashboardController;


    public function __construct()
    {
        // dépendances
        $this->session = new Session();
        $this->token = new Token($this->session);
        $this->database = new Database();
        $this->request = new Request();
        $this->view = new View($this->session);
        $this->reviewRepo = new ReviewRepository($this->database);
        $this->commentRepo = new CommentRepository($this->database);
        $this->reviewManager = new ReviewManager($this->reviewRepo, $this->commentRepo, $this->token);
        $this->commentManager = new CommentManager($this->commentRepo, $this->session, $this->token);
        $this->reviewController = new ReviewController($this->reviewManager, $this->view, $this->commentManager, $this->token, $this->session, $this->request);
        $this->commentController = new CommentController($this->commentManager, $this->request);
        $this->dashboardController = new DashboardController($this->reviewManager, $this->view, $this->request, $this->commentManager);
    }

    public function run(): void
    {
        //On test si une action a été défini ? si oui alors on récupére l'action : sinon on mets une action par défaut (ici l'action posts)
        $action = $this->request->cleanGet()['action'] ?? 'home';

        switch ($action) {
            case 'home':
                $this->reviewController->displayHomeAction();
            break;
            case 'review':
                empty($this->request->cleanGet()['id']) ? $this->reviewController->displayHomeAction() : $this->reviewController->displayOneAction();
            break;
            case 'all_reviews':
                $this->reviewController->displayAllAction();
            break;
            case 'new_comment':
                $this->commentController->newComment();
            break;
            case 'dashboard':
                $this->dashboardController->displayAllAction();
            break;
            case 'new_review':
                $this->dashboardController->reviewEditor();
            break;
            case 'publish':
                $this->dashboardController->addReviewAction();
            break;
            case 'delete_review':
                $this->dashboardController->deleteReviewAction();
            break;
            case 'update_review_page':
            $this->dashboardController->updateReviewPage();
            break;
            case 'update_review':
            $this->dashboardController->updateReviewAction();
            break;
            case 'thumb_up':
                $this->commentController->saveLikeAction();
            break;
            case 'thumb_down':
                $this->commentController->saveDislikeAction();
            break;
            case 'save_draft':
                $this->dashboardController->saveDraftAction();
            break;
            case 'show_drafts':
                $this->dashboardController->displayDraftsAction();
            break;
            case 'show_comments_to_moderate':
                $this->dashboardController->displayFlagCommentsAction();
            break;
            case 'delete_comment':
                $this->dashboardController->deleteCommentAction();
            break;
            case 'flag_comment':
                $this->commentController->flagCommentAction();
            break;
            case 'authorize_comment':
                $this->dashboardController->authorizeCommentAction();
            break;
        }

        /*if ($action === 'home') {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $postManager = new PostManager($postRepo);
            $controller = new PostController($postManager, $this->view);

            // route http://localhost:8000/?action=posts
            $this->postController->displayHomeAction();
        } elseif ($action === 'post' && isset($this->get['id'])) {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $postManager = new PostManager($postRepo);
            $controller = new PostController($postManager, $this->view);

            $commentRepo = new CommentRepository($this->database);
            $commentManager = new CommentManager($commentRepo);


            // route http://localhost:8000/?action=post&id=5
            $this->postController->displayOneAction((int)$this->get['id'], $this->commentManager);
        } else {
            echo "Error 404 - cette page n'existe pas<br><a href='index.php?action=posts'>Aller Ici</a>";
        }*/
    }
}
