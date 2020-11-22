<?php

declare(strict_types=1);

namespace App\Model\Entity;

use \DateTime;

final class Comment
{
    private $id;
    private $pseudo;
    private $content;
    private $reviewId;
    private $thumbsUp;
    private $thumbsDown;
    private $commentDate;
    private $commentStatus;

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getPseudo(): string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getReviewId(): int
    {
        return (int) $this->reviewId;
    }

    public function setReviewId(int $reviewId): self
    {
        $this->reviewId = $reviewId;
        return $this;
    }

    public function getThumbsUp(): int
    {
        return (int) $this->thumbsUp;
    }

    public function setThumbsUp(int $thumbsUp) : self
    {
        $this->thumbsUp = $thumbsUp;
        return $this;
    }

    public function getThumbsDown(): int
    {
        return (int) $this->thumbsDown;
    }

    public function setThumbsdown(int $thumbsDown) : self
    {
        $this->thumbsDown = $thumbsDown;
        return $this;
    }

    public function getCommentDate(): string
    {
        return $this->commentDate;
    }

    public function setCommentStatus(int $commentStatus): self
    {
        $this->commentStatus = $commentStatus;
        return $this;
    }

    public function getCommentStatus(): int
    {
        return (int) $this->commentStatus;
    }
}
