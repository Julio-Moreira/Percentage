<?php
namespace Julio\Percentage\Domain\Repository;

class PercentageRepository {
    private \PDO $conn;

    public function __construct(\PDO $connection) {
        $this->conn = $connection;
    }
}