<?php
namespace Julio\Percentage\Domain\Repository;

use Julio\Percentage\Domain\Model\User;
require_once '../../../vendor/autoload.php';

class PercentageRepository {
    private \PDO $conn;

    public function __construct(\PDO $connection) {
        $this->conn = $connection;
    }

    public function selectAllUsers() {
        # code...
    }

    public function getHistoryByUser(string $userName) {
        # code...
    }

    public function insertUser(User $user) {
        # code...
    }

    public function searchUser(User $user) {
        # code...
    }
}