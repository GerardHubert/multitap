<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\Model\Manager\DraftManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\AccessControl;
use App\Service\Security\Token;
use App\View\View;
use App\Service\Security\CheckActivity;

class DraftController
{
    private $draftManager;
    private $request;
    private $view;
    private $session;
    private $token;
    private $accessControl;

    public function __construct(DraftManager $draftManager, Request $request, View $view, Session $session, Token $token, AccessControl $accessControl)
    {
        $this->draftManager = $draftManager;
        $this->request = $request;
        $this->view = $view;
        $this->session = $session;
        $this->token = $token;
        $this->accessControl = $accessControl;
    }

    public function checkAccess(): void
    {
        if ($this->accessControl->isConnected() === false || $this->accessControl->getUsername() === null) {
            header('Location: index.php?action=logInPage');
            exit;
        }
    }

    public function displayDraftsAction(): void
    {
        $this->checkAccess();

        $draftStatus = 1;
        $drafts = $this->draftManager->showAllDrafts($draftStatus, (int) $this->session->getUserId());
        $this->view->render([
            'path' => 'backoffice',
            'template' => 'drafts',
            'data' => [
                'drafts' => $drafts
            ]
        ]);
    }

    public function saveDraftAction(): void
    {
        $this->checkAccess();

        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $this->draftManager->saveAsDraft($this->request->cleanPost());
                header('Location: index.php?action=show_drafts');
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    public function publishDraftFromListAction(): void
    {
        $this->checkAccess();

        $this->draftManager->publishDraftAsReview((int) $this->request->cleanGet()['id']);
        header('Location: index.php?action=dashboard&status=1');
        exit;
    }

    /**
     * Le reviewer veut transmettre son brouillon à validation pour publication
     */

    public function submitDraftToValidation(): void
    {
        $this->checkAccess();
        $token = $this->request->cleanPost()['hidden_input_token'];

        switch ($token === $this->session->getToken()) {
            case false:
                header('Location: index.php?action=home');
            exit;
            case true:
                $this->draftManager->submitDraftToValidation($this->request->cleanPost(), (int) $this->request->cleanGet()['id']);
                header('Location: index.php?action=show_drafts');
            break;           
        }
    }

    public function publishDraftAction(): void
    {
        $this->checkAccess();

        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $update = $this->draftManager->updateDraft($this->request->cleanPost(), (int) $this->request->cleanGet()['id']);
            $this->draftManager->publishDraftAsReview((int) $this->request->cleanGet()['id']);
                header('Location: index.php?action=dashboard&status=1');
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    public function updateDraftPage(): void
    {
        $this->checkAccess();

        $this->token->setToken();
        $draft = $this->draftManager->showOne((int) $this->request->cleanGet()['id']);

        $this->view->render([
            'path' => 'backoffice',
            'template' => 'editDraftPage',
            'data' => [
                'draft' => $draft
            ]
        ]);
    }

    public function updateDraftAction(): void
    {
        $this->checkAccess();

        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $id = (int) $this->request->cleanGet()['id'];
                $update = $this->draftManager->updateDraft($this->request->cleanPost(), $id);
                    if ($update === true) {
                        header('Location: index.php?action=show_drafts');
                        exit;
                    }
                    header("Location: index.php?action=update_draft_page&id=$id");
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    public function deleteDraftAction(): void
    {
        $this->checkAccess();
        
        $suppression = $this->draftManager->deleteDraft((int) $this->request->cleanGet()['id']);
        switch ($suppression) {
            case true:
                header('Location: index.php?action=dashboard&status=1');
            break;
            case false:
                header('Location: index.php?action=show_drafts');
            break;
        }
    }
}
