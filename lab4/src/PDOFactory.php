<?php

namespace CT275\Labs;

use PDO;

class PDOFactory
{
    public function create(array $config)
    {
        [
            'dbhost' => $dbhost,
            'dbname' => $dbname,
            'dbuser' => $dbuser,
            'dbpass' => $dbpass
        ] = $config;

        $dsn = "mysql:host={$dbhost};dbname={$dbname};charset=utf8mb4";
        $options = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION];
        return new PDO($dsn, $dbuser, $dbpass, $options);
    }
}