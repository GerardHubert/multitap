<?php

declare(strict_types=1);

namespace App\Model\Manager;

use App\Model\Entity\Comment;
use App\Model\Repository\CommentRepository;
use App\Service\Http\Session;
use App\Service\Security\Token;

final class CommentManager
{
    private $commentRepo;
    private $session;
    private $token;

    public function __construct(CommentRepository $commentRepository, Session $session, Token $token)
    {
        $this->commentRepo = $commentRepository;
        $this->session = $session;
        $this->token = $token;
    }

    public function showAllFromPost(int $idPost): ?array
    {
        return $this->commentRepo->findAllByPostId($idPost);
    }

    public function saveNewComment(array $post, int $reviewId): void
    {
        $formTest = empty($post['pseudo']) || empty($post['comment']);
    
        switch ($formTest) {
            // si pseudo et comment sont renseignés et si les token correspondent
            case false:
                if (!empty($this->session->getToken()) && $this->session->getToken() === $post['hidden_input']) {
                    // on instancie un nouvel objet Comment
                    $comment = new Comment();
                    $comment->setPseudo($post['pseudo']);
                    $comment->setContent($post['comment']);
                    $comment->setReviewId($reviewId);
                    
                    // si ok, on enregistre le commentaire et on redirige vers la review
                    // en supprimant le message d'erreur s'il y en a un
                    $this->session->deleteFlashMessage();
                    $this->commentRepo->create($comment);
                    header("Location: index.php?action=review&id=$reviewId#comments_area");
                    exit;
                }
                // si les token ne correspondent pas, on enregistre un message Flash et on redirige vers la même page
                $this->session->setFlashMessage("Vous ne pouvez pas poster de commentaires");
                header("Location: index.php?action=review&id=$reviewId#comments_area");
                exit;

            // si pseudo OU comment n'est pas renseigné : on affiche un message flash
            case true:
                $this->session->setFlashMessage("Merci de renseigner tous les champs");
                header("Location: index.php?action=review&id=$reviewId#comments_area");
                exit;
            
        }
    }

    public function saveLikeFromId(int $id, int $likes, int $dislikes, int $actualStatus): bool
    {
        $newLikes = $likes + 1;
        $comment = new Comment();
        $comment->setId($id);
        $comment->setThumbsUp($newLikes);
        $comment->setThumbsDown($dislikes);
        $comment->setCommentStatus($actualStatus);
        
        return $this->commentRepo->update($comment);
    }

    public function saveDislikeFromId(int $id, int $dislikes, int $likes, int $actualStatus): bool
    {
        $newDislikes = $dislikes + 1;

        //calcul du ratio de dislikes par rapport au nombre total de réactions
        $totalReactions = $newDislikes + $likes;
        $dislikesRate = floor(($newDislikes * 100) / $totalReactions);

        $comment = new Comment();
        if ($likes >= 20 && $dislikesRate >= 20 && $actualStatus === 0) {
            $status = 1;
            $comment->setCommentStatus($status);
            $comment->setId($id);
            $comment->setThumbsDown($newDislikes);
            $comment->setThumbsUp($likes);
        } else {
            $comment->setId($id);
            $comment->setThumbsDown($newDislikes);
            $comment->setThumbsUp($likes);
            $comment->setCommentStatus($actualStatus);
        }
        
        return $this->commentRepo->update($comment);
    }

    public function showAllFromStatus(int $commentStatus): ?array
    {
        return $this->commentRepo->findAllByStatus($commentStatus);
    }

    public function deleteFromId(int $id): bool
    {
        $commentToDelete = $this->commentRepo->findOneById($id);
        return $this->commentRepo->delete($commentToDelete, $id);
    }

    public function flagFromId(int $id, int $commentStatus, int $likes, int $dislikes): bool
    {
        $comment = new Comment();
        $comment->setId($id);
        $comment->setCommentStatus($commentStatus);
        $comment->setThumbsUp($likes);
        $comment->setThumbsDown($dislikes);


        return $this->commentRepo->update($comment);
    }

    public function authorizeComment(int $id, int $status, int $likes, int $dislikes): bool
    {
        $comment = new Comment();
        $comment->setId($id);
        $comment->setCommentStatus($status);
        $comment->setThumbsUp($likes);
        $comment->setThumbsDown($dislikes);

        return $this->commentRepo->update($comment);
    }
}
