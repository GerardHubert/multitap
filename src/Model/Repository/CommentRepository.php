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
        $comments = [];
        $request =  $this->database->prepare("SELECT *, DATE_FORMAT(commentDate, '%d %b. %Y à %H:%i:%s') AS commentDate
            FROM comments
            INNER JOIN users
            ON users.userId = comments.userId
            WHERE reviewId = :review_id
            ORDER BY commentDate DESC");
        $request->bindParam(':review_id', $idPost, PDO::PARAM_INT);
        $request->setFetchMode(PDO::FETCH_CLASS, Comment::class);
        $request->execute();
        $comments = $request->fetchAll();

        return $comments;
    }

    public function findAllByStatus($status): ?array
    {
        $request = $this->database->prepare("SELECT *, DATE_FORMAT(commentDate, '%d-%b-%Y à %H:%i:%s') AS commentDate
            FROM comments
            INNER JOIN users
            ON users.userId = comments.userId
            WHERE commentStatus = :commentStatus
            ORDER BY commentDate DESC");
        $request->bindParam(':commentStatus', $status);
        $request->setFetchMode(PDO::FETCH_CLASS, Comment::class);
        $request->execute();
        $flagComments = $request->fetchAll();

        return $flagComments;
    }

    public function findOneById(int $id): ?Comment
    {
        $request = $this->database->prepare("SELECT *
            FROM comments
            INNER JOIN users
            ON users.userId = comments.userId
            WHERE commentId = :id");
        $request->bindParam(':id', $id);
        $request->setFetchMode(PDO::FETCH_CLASS, Comment::class);
        $request->execute();
        $comment = $request->fetch();

        return $comment;
    }

    public function create(Comment $comment) : void
    {
        $userId = $comment->getUserId();
        $content = $comment->getContent();
        $reviewId = $comment->getReviewId();

        $request = $this->database->prepare('INSERT INTO comments (userId, content, reviewId, commentDate)
            VALUES (:userId, :content, :idPost, NOW())');
        $request->bindParam(':userId', $userId);
        $request->bindParam(':content', $content);
        $request->bindParam(':idPost', $reviewId);

        $request->execute();
    }

    public function update(Comment $comment) : bool
    {
        $id = $comment->getCommentId();
        $likes = $comment->getThumbsUp();
        $dislikes = $comment->getThumbsDown();
        $status = $comment->getCommentStatus();

        $request = $this->database->prepare('UPDATE comments
            SET thumbsUp = :newThumbsUp, thumbsDown = :newThumbsDown, commentStatus = :commentStatus
            WHERE commentId = :id');
        $request->bindParam(':newThumbsUp', $likes);
        $request->bindParam(':newThumbsDown', $dislikes);
        $request->bindParam(':id', $id);
        $request->bindParam(':commentStatus', $status);

        return $request->execute();
    }

    public function delete(Comment $comment) : bool
    {
        $id = $comment->getCommentId();
        $request = $this->database->prepare("DELETE FROM comments
            WHERE commentId = :id");
        $request->bindParam(':id', $id);
        
        return $request->execute();
    }
}
