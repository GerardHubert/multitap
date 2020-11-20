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
        // dépendance
        $this->session = new Session();
        $this->token = new Token($this->session);
        $this->database = new Database();
        $this->view = new View($this->session);
        $this->reviewRepo = new ReviewRepository($this->database);
        $this->commentRepo = new CommentRepository($this->database);
        $this->reviewManager = new ReviewManager($this->reviewRepo, $this->commentRepo, $this->token);
        $this->commentManager = new CommentManager($this->commentRepo, $this->session, $this->token);
        $this->reviewController = new ReviewController($this->reviewManager, $this->view, $this->commentManager, $this->token, $this->session);
        $this->commentController = new CommentController($this->commentManager);
        $this->request = new Request();
        $this->dashboardController = new DashboardController($this->reviewManager, $this->view, $this->request);
        $this->get = $this->request->cleanGet();
        $this->post = $this->request->cleanPost();
    }

    public function run(): void
    {
        //On test si une action a été défini ? si oui alors on récupére l'action : sinon on mets une action par défaut (ici l'action posts)
        $action = $this->get['action'] ?? 'home';

        switch ($action) {
            case 'home':
                $this->reviewController->displayHomeAction();
            break;
            case 'review':
                empty($this->get['id']) ? $this->reviewController->displayHomeAction() : $this->reviewController->displayOneAction((int) $this->get['id']);
            break;
            case 'all_reviews':
                $this->reviewController->displayAllAction((int) $this->get['page']);
            break;
            case 'new_comment':
                $this->commentController->newComment($this->post, (int) $this->get['id']);
            break;
            case 'dashboard':
                $this->dashboardController->displayAllAction();
            break;
            case 'new_review':
                $this->dashboardController->reviewEditor();
            break;
            case 'publish':
                $this->dashboardController->addReviewAction($this->request->cleanPost());
            break;
            case 'delete_review':
                $this->dashboardController->deleteReviewAction((int) $this->request->cleanGet()['id']);
            break;
            case 'edit_review':
                $this->dashboardController->editReviewPage((int) $this->request->cleanGet()['id']);
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
