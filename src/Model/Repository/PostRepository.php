<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Service\Database;

final class PostRepository
{
    private /*Database*/ $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function findById(int $id): ?Post
    {
        $data = $this->database->executeSqlDB(['id'=>$id]);
        // réfléchir à l'hydratation des entités;
        return $data === null ? $data : new Post($data['id'], $data['title'], $data['text'], $data['reviewer'], $data['date'], $data['image']);
    }

    public function findByAll(): array
    {
        // SB ici faire l'hydratation des objets
        
        $objects = [];
        $data = $this->database->prepare('SELECT * FROM reviews');
        $data->execute();
        $posts = $data->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($posts as $post) {
            array_push($objects, new Post($post));
        }
        return $objects;

        /*if ($data === null) {
            return null;
        }*/
        
        // réfléchir à l'hydratation des entités;
        /*$posts = [];
        foreach ($data as $post) {
            $posts[] = new Post((int)$post['id'], $post['title'], $post['text'], $post['reviewer'], $post['date'], $post['image']);
        }

        return $posts;*/
    }

    public function create(Post $post) : bool
    {
        return false;
    }

    public function update(Post $post) : bool
    {
        return false;
    }

    public function delete(Post $post) : bool
    {
        return false;
    }
}
