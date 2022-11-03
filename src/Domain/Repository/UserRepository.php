<?php
namespace julio\percentage\Domain\Repository;

use  julio\percentage\Domain\Model\User;

class UserRepository {
    private \PDO $conn;

    public function __construct(\PDO $connection) {
        $this->conn = $connection;
    }

    /** 
     * Get all users saved in db
     */
    public function selectAllUsers(): array {
        $statement = $this->conn->query('SELECT * FROM user;');
        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return $result;
    }

    /**
     * insert new user on db
     */
    public function insertUser(string $name, int $id): void {
        $statement = $this->conn->prepare('INSERT INTO user VALUES (:id, :name, 0)');
        $statement->execute([
            ':id' => $id,
            ':name' => $name
        ]);
    }

    /** 
     * Confer if the user exists 
    */
    public function userExists(int $id): bool {
        $statement = $this->conn->prepare('SELECT count(*) FROM user WHERE id = ?');
        $statement->bindValue(1, $id, \PDO::PARAM_INT);
        $statement->execute();

        $userCount = $statement->fetchColumn(0);

        return $userCount >= 1 ? true : false;
    }

    /**
     * Get user accounts saved on db
     */
    public function getHistoryOfUser(User $user): array {
        $statement = $this->conn->prepare('SELECT h.account, h.result, u.id 
                                                  FROM history AS h 
                                                  JOIN user AS u ON h.user = u.id
                                                  WHERE u.id = ?;');

        $statement->bindValue(1, $user->getId(), \PDO::PARAM_INT);
        $statement->execute();

        $historyDataList = $statement->fetchAll(\PDO::FETCH_ASSOC);
        return $this->hydrateHistoryList($historyDataList);
    }

    private function hydrateHistoryList($historyDataList): array {
        $history = [];

        foreach ($historyDataList as $historyData) {
            $history[] = [
                $historyData['account'] . " = " . $historyData['result'],
                $historyData['id']
            ];
        }

        return $history;
    }
}