<?php

declare(strict_types=1);

namespace App\Model\Entity;

final class Comment
{
    private /*int*/ $id;
    private /*string*/ $pseudo;
    private /*string*/ $text;
    private /*int*/ $idPost;

    public function __construct(int $id, string $pseudo, string $text, int $idPost)
    {
        $this->id = $id;
        $this->pseudo = $pseudo;
        $this->text = $text;
        $this->idPost = $idPost;
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
}
