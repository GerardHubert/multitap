<?php

declare(strict_types=1);

namespace  App\Controller\Backoffice;

use App\Model\Manager\DraftManager;
use App\Service\Http\Request;
use App\View\View;

class DraftController
{
    private $draftManager;
    private $request;
    private $view;

    public function __construct(DraftManager $draftManager, Request $request, View $view)
    {
        $this->draftManager = $draftManager;
        $this->request = $request;
        $this->view = $view;
    }

    public function displayDraftsAction(): void
    {
        $draftStatus = 1;
        $drafts = $this->draftManager->showAllDrafts($draftStatus);
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
        $this->draftManager->saveAsDraft($this->request->cleanPost());

        header('Location: index.php?action=dashboard');
        exit;
    }

    public function publishDraftAction(): void
    {
        $this->draftManager->publishDraftAsReview((int) $this->request->cleanGet()['id']);
        
        header('Location: index.php?action=show_drafts');
        exit;
    }

    public function updateDraftPage(): void
    {
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
        $id = (int) $this->request->cleanGet()['id'];
        $update = $this->draftManager->updateDraft($this->request->cleanPost(), $id);
        switch ($update) {
            case true:
                header('Location: index.php?action=show_drafts');
            exit;
            case false:
                header("Location: index.php?action=update_draft_page&id=$id");
            exit;
        }
    }

    public function deleteDraftAction(): void
    {
        $suppression = $this->draftManager->deleteDraft((int) $this->request->cleanGet()['id']);
        switch ($suppression) {
            case true:
                header('Location: index.php?action=dashboard');
                exit;
            break;
            case false:
                header('Location: index.php?action=show_drafts');
                exit;
            break;
        }
    }

}