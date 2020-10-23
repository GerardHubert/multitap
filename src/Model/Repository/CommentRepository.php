<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Comment;
use App\Service\Database;

final class CommentRepository
{
    private /*Database*/ $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findAllByPostId(int $idPost): ?array
    {
        // SB ici faire l'hydratation des objets
        $comments = [];
        $request =  $this->database->prepare('SELECT *
            FROM comments
            WHERE review_id = :review_id');
        $request->bindParam(':review_id', $idPost);
        $request->execute();
        $data = $request->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($data as $comment) {
            $comments[] = new Comment($comment);
        }

        return $comments;

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

    public function create(Comment $comment) : bool
    {
        return false ;
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
