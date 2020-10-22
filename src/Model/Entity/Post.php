<?php

declare(strict_types=1);

namespace App\Model\Entity;

use DateTime;

final class Post
{
    private $id;
    private $title;
    private $text;
    private $reviewer;
    private $date;
    private $image;
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
        $this->title = $data['title'];
        $this->text = $data['content'];
        $this->reviewer = $data['reviewer'];
        $this->date = $data['date'];
        $this->image = $data['image'];
        
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
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
}
