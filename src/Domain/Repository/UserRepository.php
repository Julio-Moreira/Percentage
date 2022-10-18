<?php
namespace julio\percentage\Domain\Repository;

use  julio\percentage\Domain\Model\User;

class UserRepository {
    private \PDO $conn;

    public function __construct(\PDO $connection) {
        $this->conn = $connection;
    }

    /** Get all users saved in db */
    public function selectAllUsers(): array {
        $statement = $this->conn->query('SELECT * FROM user;');
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    public function getHistoryByUser(User $user) {
        
    }

    public function insertUser(User $user) {
        # code...
    }

    /** 
     * Confer if the user exists 
     * 
     * @return true if user exists or false if not exists
    */
    public function userExists(User $user): bool {
        $statement = $this->conn->prepare('SELECT count(*) FROM user WHERE id = ?');
        $statement->bindValue(1, $user->getId(), \PDO::PARAM_INT);
        $statement->execute();

        $userCount = $statement->fetchColumn(0);

        return $userCount >= 1 ? true : false;
    }
}