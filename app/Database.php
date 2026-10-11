<?php

namespace App;

use PDO;
use PDOStatement;

class Database
{
    private PDO $db;

    public function __construct()
    {
        $this->db = new PDO(
            $this->getDsn(),
            getenv("DB_USER"),
            getenv("DB_PASSWORD"),
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ],
        );
    }

    public function query(string $query, ?string $class, array $params = []): PDOStatement | false
    {
        $prepare = $this->db->prepare($query);

        if (!is_null($class)) {
            $prepare->setFetchMode(PDO::FETCH_CLASS, $class);
        }

        return $prepare->execute($params);
    }

    private function getDsn(): string
    {
        $host = getenv("DB_HOST");
        $port = getenv("DB_PORT");
        $dbName = getenv("DB_NAME");

        return "pgsql:host={$host};port={$port};dbname={$dbName}";
    }
}
