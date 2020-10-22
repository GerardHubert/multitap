<?php

declare(strict_types=1);

namespace  App\Service;

use App\Controller\Frontoffice\PostController;
use App\Model\Manager\{CommentManager, PostManager};
use App\Model\Repository\{CommentRepository, PostRepository};
use App\View\View;
use App\Model\Entity\Post;

// cette classe router est un exemple très basic. Cette façon de faire n'est pas optimale
final class Router
{
    private /*Database*/ $database;
    private /*View*/ $view;
    private /*array*/ $get;

    public function __construct()
    {
        // dépendance
        $this->database = new Database();
        $this->view = new View();

        // En attendent de mettre ne place la class App\Service\Http\Request
        $this->get = $_GET;
    }

    public function run(): void
    {
        // Nous avons deux routes :
        // - une pour afficher tous les posts => http://localhost:8000/?action=posts
        // - une pour afficher un post en particulier => http://localhost:8000/?action=post&id=5
        
        //On test si une action a été défini ? si oui alors on récupére l'action : sinon on mets une action par défaut (ici l'action posts)
        $action = isset($this->get['action']) ? $this->get['action'] : 'posts';

        //Déterminer sur quelle route nous sommes // Attention algorithme naïf
        if ($action === 'posts') {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $postManager = new PostManager($postRepo);
            $controller = new PostController($postManager, $this->view);
    
            // route http://localhost:8000/?action=posts
            $controller->displayAllAction();
        } elseif ($action === 'post' && isset($this->get['id'])) {
            //injection des dépendances et instanciation du controller
            $postRepo = new PostRepository($this->database);
            $postManager = new PostManager($postRepo);
            $controller = new PostController($postManager, $this->view);

            $commentRepo = new CommentRepository($this->database);
            $commentManager = new CommentManager($commentRepo);
                
            
            // route http://localhost:8000/?action=post&id=5
            $controller->displayOneAction((int)$this->get['id'], $commentManager);
        } else {
            echo "Error 404 - cette page n'existe pas<br><a href='index.php?action=posts'>Aller Ici</a>";
        }
    }
}
