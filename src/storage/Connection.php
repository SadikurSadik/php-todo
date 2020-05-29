<?php

namespace todo\Storage;

use PDO;

class Connection
{
    protected $conn;

    public function __construct()
    {
        if(empty($this->conn)) {
            $dbConfig = parse_ini_file(__DIR__.'/../../config.ini');
            try {
                $this->conn = new PDO("mysql:host={$dbConfig['server']};port={$dbConfig['port']};dbname={$dbConfig['dbname']}", $dbConfig['user'], $dbConfig['password']);
            } catch (\PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
            }
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
}
