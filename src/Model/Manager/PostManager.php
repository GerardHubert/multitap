<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Post;
use App\Model\Repository\PostRepository;

final class PostManager
{
    private /*PostRepository*/ $postRepo;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepo = $postRepository;
    }

    public function showAll(): array
    {
        // renvoie tous les posts
        return $this->postRepo->findByAll();
    }

    public function showOne(int $id): ?Post
    {
        return $this->postRepo->findById($id);
    }
}
