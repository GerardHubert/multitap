<?php

declare(strict_types=1);

namespace  App\Service;

use App\Controller\Backoffice\DashboardController;
use App\Controller\Backoffice\DraftController;
use App\Controller\Backoffice\UserController;
use App\Controller\Frontoffice\CommentController;
use App\Controller\Frontoffice\ReviewController;
use App\Model\Manager\CommentManager;
use App\Model\Manager\DraftManager;
use App\Model\Manager\ReviewManager;
use App\Model\Manager\UserManager;
use App\Model\Repository\CommentRepository;
use App\Model\Repository\ReviewRepository;
use App\Model\Repository\UserRepository;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\AccessControl;
use App\Service\Security\Token;
use App\View\View;

final class Router
{
    private $database;
    private $view;
    private $reviewRepo;
    private $commentRepo;
    private $reviewManager;
    private $commentManager;
    private $draftManager;
    private $reviewController;
    private $draftController;
    private $commentController;
    private $session;
    private $token;
    private $request;
    private $dashboardController;
    private $userController;
    private $userManager;
    private $userRepository;
    private $accessControl;


    public function __construct()
    {
        // dépendances
        $this->session = new Session();
        $this->accessControl = new AccessControl($this->session);
        $this->token = new Token($this->session);
        $this->database = new Database();
        $this->request = new Request();
        $this->view = new View($this->session);
        $this->userRepository = new UserRepository($this->database);
        $this->reviewRepo = new ReviewRepository($this->database);
        $this->commentRepo = new CommentRepository($this->database);
        $this->reviewManager = new ReviewManager($this->reviewRepo, $this->commentRepo, $this->token);
        $this->draftManager = new draftManager($this->reviewRepo, $this->token);
        $this->commentManager = new CommentManager($this->commentRepo, $this->session, $this->token);
        $this->userManager = new UserManager($this->userRepository, $this->session);
        $this->draftController = new draftController($this->draftManager, $this->request, $this->view, $this->session, $this->token);
        $this->reviewController = new ReviewController($this->reviewManager, $this->view, $this->commentManager, $this->token, $this->session, $this->request);
        $this->commentController = new CommentController($this->commentManager, $this->request);
        $this->dashboardController = new DashboardController($this->reviewManager, $this->view, $this->request, $this->commentManager, $this->token, $this->session, $this->accessControl);
        $this->userController = new UserController($this->view, $this->request, $this->token, $this->session, $this->userManager, $this->accessControl, $this->reviewManager);
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
                $this->dashboardController->displayUserReviewsAction();
            break;
            case 'user_parameters_page':
                $this->userController->userParametersPage();
            break;
            case 'new_review':
                $this->dashboardController->reviewEditor();
            break;
            case 'submit_to_validation':
                $this->dashboardController->submitReviewAction();
            break;
            case 'submit_update_to_validation':
                $this->dashboardController->submitUpdateAction();
            break;
            case 'show_waiting_reviews':
                $this->dashboardController->showAwaitingReviewsAction();
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
                $this->draftController->saveDraftAction();
            break;
            case 'show_drafts':
                $this->draftController->displayDraftsAction();
            break;
            case 'publish_draft':
                $this->draftController->publishDraftAction();
            break;
            case 'publish_draft_from_list':
                $this->draftController->publishDraftFromListAction();
            break;
            case 'update_draft_page':
                $this->draftController->updateDraftPage();
            break;
            case 'update_draft':
                $this->draftController->updateDraftAction();
            break;
            case 'delete_draft':
                $this->draftController->deleteDraftAction();
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
            case 'signInPage':
                $this->userController->signInPage();
            break;
            case 'sign-in':
                $this->userController->newUserAction();
            break;
            case 'logInPage':
                $this->userController->logInPage();
            break;
            case 'log-in':
                $this->userController->logInAction();
            break;
            case 'logout':
                $this->userController->logOutAction();
            break;
            case 'members_management':
                $this->userController->membersManagementPage();
            break;
            case 'deleteUser':
                $this->userController->deleteUSerAction();
            break;
            case 'update_password':
                $this->userController->updatePasswordAction();
            break;
            case 'update_username_and_email':
                $this->userController->updateUsernameAndEmail();
            break;
            case 'show_reviews_to_validate':
                $this->dashboardController->showReviewsToValidate();
            break;
            case 'validate_review':
                $this->dashboardController->validateReviewAction();
            break;
            case 'change_rank_demand':
                $this->userController->updateRankDemand();
            break;
            case 'change_rank_validation':
                $this->userController->updateRankValidation();
            break;
            case 'cancel_rank_demand':
                $this->userController->updateRankCancel();
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
