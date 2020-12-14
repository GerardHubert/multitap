<?php

declare(strict_types=1);

namespace App\Model\Entity;

use DateTime;

final class Review
{
    private $reviewId;
    private $reviewTitle;
    private $gameTitle;
    private $content;
    //private $reviewer;
    private $userId;
    private $reviewDate;
    private $apiGameId;
    private $reviewStatus;

    public function getReviewId(): int
    {
        return (int) $this->reviewId;
    }

    public function getReviewTitle(): string
    {
        return $this->reviewTitle;
    }

    public function setReviewTitle(string $reviewTitle): self
    {
        $this->reviewTitle = $reviewTitle;
        return $this;
    }

    public function getGameTitle() : string
    {
        return $this->gameTitle;
    }

    public function setGameTitle(string $gameTitle): self
    {
        $this->gameTitle = $gameTitle;
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

    /*public function getReviewer(): string
    {
        return $this->reviewer;
    }

    public function setReviewer(string $reviewer): self
    {
        $this->reviewer = $reviewer;
        return $this;
    }*/

    public function setUserId(int $userId): self
    {
        $this->userId = $userId;
        return $this;
    }

    public function getUserId(): int
    {
        return (int) $this->userId;
    }

    public function getReviewDate(): string
    {
        return $this->reviewDate;
    }

    public function getApiGameId(): int
    {
        return (int) $this->apiGameId;
    }

    public function setApiGameId(int $apiGameId): self
    {
        $this->apiGameId = $apiGameId;
        return $this;
    }

    public function setReviewStatus(int $reviewStatus): self
    {
        $this->reviewStatus = $reviewStatus;
        return $this;
    }

    public function getReviewStatus(): int
    {
        return (int) $this->reviewStatus;
    }
}
