<?php

declare(strict_types=1);

namespace App\Controller\Frontoffice;

use App\Model\Manager\CommentManager;
use App\Service\Http\Request;
use App\Service\Http\Session;

final class CommentController
{
    private $commentManager;
    private $request;
    private $session;

    public function __construct(CommentManager $commentManager, Request $request, Session $session)
    {
        $this->commentManager = $commentManager;
        $this->request = $request;
        $this->session = $session;
    }

    public function newComment() : void
    {
        $this->commentManager->saveNewComment($this->request->cleanPost(), (int) $this->request->cleanGet()['id']);
    }

    public function saveLikeAction(): void
    {
        $commentId = $this->request->cleanGet()['id'];
        $likes = $this->request->cleanGet()['actual_likes'];
        $dislikes = $this->request->cleanGet()['actual_dislikes'];
        $reviewId = $this->request->cleanGet()['review'];
        $actualStatus = $this->request->cleanGet()['actual_status'];

        $this->commentManager->saveLikeFromId((int) $commentId, (int) $likes, (int) $dislikes, (int) $actualStatus);

        header("Location: index.php?action=review&id=$reviewId#comments_list");
        exit;
    }

    public function saveDislikeAction(): void
    {
        $commentId = $this->request->cleanGet()['id'];
        $dislikes = $this->request->cleanGet()['actual_dislikes'];
        $likes = $this->request->cleanGet()['actual_likes'];
        $reviewId = $this->request->cleanGet()['review'];
        $actualStatus = $this->request->cleanGet()['actual_status'];

        $this->commentManager->saveDislikeFromId((int) $commentId, (int) $dislikes, (int) $likes, (int) $actualStatus);

        header("Location: index.php?action=review&id=$reviewId#comments_list");
        exit;
    }

    public function flagCommentAction(): void
    {
        $commentStatus = 1;
        $reviewId = $this->request->cleanGet()['review'];
        $likes = $this->request->cleanGet()['actual_likes'];
        $dislikes = $this->request->cleanGet()['actual_dislikes'];
        $this->commentManager->flagFromId((int) $this->request->cleanGet()['id'], $commentStatus, (int) $likes, (int) $dislikes);
        
        header("Location: index.php?action=review&id=$reviewId");
        exit;
    }
}
