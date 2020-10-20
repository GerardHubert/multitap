<?php

declare(strict_types=1);

namespace App\Model\Entity;

use DateTime;

final class Post
{
    private /*int*/ $id;
    private /*string*/ $title;
    private /*string*/ $text;
    private $reviewer;
    private $date;
    private $image;

    public function __construct(int $id, string $title, string $text, string $reviewer, string $date, string $image)
    {
        $this->id = $id;
        $this->title = $title;
        $this->text = $text;
        $this->reviewer = $reviewer;
        $this->date = $date;
        $this->image = $image;
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
