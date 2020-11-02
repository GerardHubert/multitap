<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Comment;
use App\Model\Repository\CommentRepository;

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

    public function saveNewComment(array $post, int $reviewId): void
    {
        // on vérifie d'abord si tous les champs sont renseignés : pseudo et commentaire
        if (!empty($post['pseudo']) && !empty($post['comment'])) {
            // on instancie un nouvel objet Comment
            $comment = new Comment();
            $comment->setPseudo($post['pseudo']);
            $comment->setContent($post['comment']);
            $comment->setReviewId($reviewId);
            
            // si ok, on enregistre le commentaire et on redirige vers la review
            $this->commentRepo->create($comment);
            header("Location: index.php?action=review&id=$reviewId");
            exit;
        }
        // si pas ok, on redirige vers la page de la review + message flash (champs incomplets)
    }
}
