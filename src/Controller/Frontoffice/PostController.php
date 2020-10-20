<?php

declare(strict_types=1);

namespace  App\Controller\Frontoffice;

use App\Model\Manager\CommentManager;
use App\Model\Manager\PostManager;
use App\View\View;

final class PostController
{
    private /*PostManager*/ $postManager;
    private /*View*/ $view;

    public function __construct(PostManager $postManager, View $view)
    {
        $this->postManager = $postManager;
        $this->view = $view;
    }
    
    public function displayOneAction(int $id, CommentManager $commentManager): void
    {
        $post = $this->postManager->showOne($id);
        $comments = $commentManager->showAllFromPost($id);

        if ($post !== null) {
            $this->view->render(
                [
                'template' => 'post',
                'data' => [
                    'post' => $post,
                    'comments' => $comments,
                    ],
                ],
            );
        } elseif ($post === null) {
            echo '<h1>faire une redirection vers la page d\'erreur, ce post n\'existe pas</h1><a href="index.php?action=posts">Liste des posts</a><br>';
        }
    }

    public function displayAllAction(): void
    {
        $posts = $this->postManager->showAll();

        /*$this->view->render([
            'template' => 'posts',
            'data' => ['posts' => $posts],
        ]);*/

        print_r($posts);
    }
}
