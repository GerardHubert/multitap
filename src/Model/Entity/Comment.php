<?php

declare(strict_types=1);

namespace App\Model\Entity;

final class Comment
{
    private /*int*/ $id;
    private /*string*/ $pseudo;
    private /*string*/ $text;
    private /*int*/ $idPost;
    private $thumbsUp;
    private $thumbsDown;
    private $commentDate;

    public function __construct(array $comments)
    {
        $this->id = $comments['id'];
        $this->pseudo = $comments['pseudo'];
        $this->text = $comments['content'];
        $this->idPost = $comments['review_id'];
        $this->thumbsUp = (int) $comments['thumbs_up'];
        $this->thumbsDown = (int) $comments['thumbs_down'];
        $this->commentDate = $comments['comment_date'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getpseudo(): string
    {
        return $this->pseudo;
    }

    public function setpseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
        return $this;
    }

    public function getIdPost(): int
    {
        return $this->idPost;
    }

    public function getThumbsUp(): int
    {
        return $this->thumbsUp;
    }

    public function getThumbsDown(): int
    {
        return $this->thumbsDown;
    }

    public function getCommentDate(): string
    {
        return $this->commentDate;
    }
}
