<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Repository\CommentRepository;
use APP\Model\Entity\Comment;

final class CommentManager
{
    private $commentRepo;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepo = $commentRepository;
    }

    public function showAllFromPost(int $idPost): ?array
    {
        return $this->commentRepo->findAllByPostId($idPost);
    }
}
