<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\Model\Manager\DraftManager;
use App\Service\Http\Request;
use App\Service\Http\Session;
use App\Service\Security\Token;
use App\View\View;

class DraftController
{
    private $draftManager;
    private $request;
    private $view;
    private $session;
    private $token;

    public function __construct(DraftManager $draftManager, Request $request, View $view, Session $session, Token $token)
    {
        $this->draftManager = $draftManager;
        $this->request = $request;
        $this->view = $view;
        $this->session = $session;
        $this->token = $token;
    }

    public function displayDraftsAction(): void
    {
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
        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $this->draftManager->saveAsDraft($this->request->cleanPost());
                header('Location: index.php?action=dashboard');
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    public function publishDraftFromListAction(): void
    {
        $this->draftManager->publishDraftAsReview((int) $this->request->cleanGet()['id']);
        header('Location: index.php?action=dashboard');
        exit;
    }

    public function publishDraftAction(): void
    {
        $inputToken = $this->request->cleanPost()['hidden_input_token'];
        $sessionToken = $this->session->getToken();

        switch ($inputToken === $sessionToken) {
            case true:
                $update = $this->draftManager->updateDraft($this->request->cleanPost(), (int) $this->request->cleanGet()['id']);
            $this->draftManager->publishDraftAsReview((int) $this->request->cleanGet()['id']);
                header('Location: index.php?action=dashboard');
            break;
            case false:
                header('Location: index.php?action=home');
            break;
        }
    }

    public function updateDraftPage(): void
    {
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
        $suppression = $this->draftManager->deleteDraft((int) $this->request->cleanGet()['id']);
        switch ($suppression) {
            case true:
                header('Location: index.php?action=dashboard');
            break;
            case false:
                header('Location: index.php?action=show_drafts');
            break;
        }
    }
}
