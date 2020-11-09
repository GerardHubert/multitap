<?php

declare(strict_types=1);

namespace App\Service;

use \PDO;

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
