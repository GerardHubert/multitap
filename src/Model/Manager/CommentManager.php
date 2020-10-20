<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Repository\CommentRepository;

final class CommentManager
{
    private /*CommentRepository*/ $commentRepo;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepo = $commentRepository;
    }

    public function showAllFromPost(int $idPost): ?array
    {
        return $this->commentRepo->findAllByPostId($idPost);
    }
}
