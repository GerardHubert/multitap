<?php

declare(strict_types=1);
// class pour gérer la connection à la base de donnée
namespace App\Service;
use \PDO;

// *** exemple fictif d'accès à la base de données
final class Database extends PDO
{
    private $dsn = "mysql:dbname=multitap;host=localhost; port=3308;charset=utf8mb4";
    private $user = 'root';
    private $password = '';
    
    public function __construct()
    {
        parent::__construct($this->dsn, $this->user, $this->password);
    }
}
