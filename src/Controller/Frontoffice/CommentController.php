<?php

declare(strict_types=1);

namespace App\Controller\Frontoffice;

use App\Model\Manager\CommentManager;

final class CommentController
{
    private $commentManager;

    public function __construct(CommentManager $commentManager)
    {
        $this->commentManager = $commentManager;
    }

    public function newComment(array $post, int $reviewId) : void
    {
        $this->commentManager->saveNewComment($post, $reviewId);

        // selon ce qui est retourné, on affiche :
            // un message d'erreur (champs obligatoires non renseignés),


            // ou la page de la review avec le nouveau commentaire
    }
}
