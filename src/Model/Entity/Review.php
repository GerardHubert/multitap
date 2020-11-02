<?php

declare(strict_types=1);

namespace App\Model\Entity;

use DateTime;

final class Review
{
    private $id;
    private $reviewTitle;
    private $gameTitle;
    private $content;
    private $reviewer;
    private $reviewDate;
    private $image;
    private $apiGameId;
    private $video;

    public function getId(): int
    {
        return (int) $this->id;
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

    public function getReviewer(): string
    {
        return $this->reviewer;
    }

    public function setReviewer(string $reviewer): self
    {
        $this->reviewer = $reviewer;
        return $this;
    }

    public function getReviewDate(): string
    {
        return $this->reviewDate;
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;
        return $this;
    }

    public function getApiGameId(): int
    {
        return (int) $this->apiGameId;
    }

    public function getVideo(): string
    {
        return $this->video;
    }

    public function setVideo(string $videoLink): self
    {
        $this->video = $videoLink;
        return $this;
    }
}
