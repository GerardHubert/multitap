<?php

declare(strict_types=1);

namespace App\Model\Repository;

use \PDO;
use App\Model\Entity\Comment;
use App\Service\Database;

final class CommentRepository
{
    private $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findAllByPostId(int $idPost): ?array
    {
        // SB ici faire l'hydratation des objets
        $comments = [];
        $request =  $this->database->prepare("SELECT *, DATE_FORMAT(commentDate, '%d-%m-%Y à %H:%i:%s') AS commentDate
            FROM comments
            WHERE reviewId = :review_id");
        $request->bindParam(':review_id', $idPost);
        $request->setFetchMode(PDO::FETCH_CLASS, Comment::class);
        $request->execute();
        $comments = $request->fetchAll();

        return $comments;

        /*foreach ($data as $comment) {
            $comments[] = new Comment($comment);
        }*/
        
        

        /*if ($data === null) {
            return null;
        }

        // réfléchir à l'hydratation des entités;
        $comments = [];
        foreach ($data as $comment) {
            $comments[] = new Comment((int)$comment['id'], $comment['pseudo'], $comment['text'], (int)$comment['idPost']);
        }

        return $comments;*/
    }

    public function findByAll(): ?array
    {
        return null;
    }

    public function create(Comment $comment) : void
    {
        $pseudo = $comment->getPseudo();
        $content = $comment->getContent();
        $reviewId = $comment->getReviewId();

        $request = $this->database->prepare('INSERT INTO comments (pseudo, content, reviewId, commentDate)
            VALUES (:pseudo, :content, :idPost, NOW())');
        $request->bindParam(':pseudo', $pseudo);
        $request->bindParam(':content', $content);
        $request->bindParam(':idPost', $reviewId);

        var_dump($request->execute());
    }

    public function update(Comment $comment) : bool
    {
        return false;
    }

    public function delete(Comment $comment) : bool
    {
        return false;
    }
}
