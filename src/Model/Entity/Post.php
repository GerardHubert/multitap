<?php

declare(strict_types=1);

namespace App\Model\Entity;

use DateTime;

final class Post
{
    private $id;
    private $reviewTitle;
    private $gameTitle;
    private $text;
    private $reviewer;
    private $date;
    private $image;
    private $apiGameId;
    private $data;

    public function __construct(/*int $id, string $title, string $content, string $reviewer, string $date, string $image*/array $data)
    {
        $this->data = $data;
        /*$this->id = $id;
        $this->title = $title;
        $this->text = $content;
        $this->reviewer = $reviewer;
        $this->date = $date;
        $this->image = $image;*/
        $this->id = (int) $data['id'];
        $this->reviewTitle = $data['review_title'];
        $this->gameTitle = $data['game_title'];
        $this->text = $data['content'];
        $this->reviewer = $data['reviewer'];
        $this->date = $data['date'];
        $this->image = $data['image'];
        $this->apiGameId = (int) $data['api_game_id'];
        
    }

    public function getId(): int
    {
        return $this->id;
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

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): self
    {
        $this->text = $text;
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

    public function getDate(): string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;
        return $this;
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
        return $this->apiGameId;
    }
}
