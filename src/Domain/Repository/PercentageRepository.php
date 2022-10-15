<?php
namespace Julio\Percentage\Domain\Repository;

use Julio\Percentage\Domain\Model\User;
require_once '../../../vendor/autoload.php';

class PercentageRepository {
    private \PDO $conn;

    public function __construct(\PDO $connection) {
        $this->conn = $connection;
    }

    /** Save account in db */
    public function saveHistory(string $account, int $result, User $user): bool {
        $insertQuery = "INSERT INTO history VALUES (:account, :result, :user);";
        $statement = $this->conn->prepare($insertQuery);
        $statement->execute([
            ':account' => $account,
            ':result' => $result,
            ':user' => $user->getId()
        ]);
        
        return true;
    }

    /** 
     * Get all history saved in db
     * 
     * @return array of all history
     */
    public function allHistory(): array {
        $allHistory = $this->conn->query('SELECT h.account, h.result, 
        u.name, u.id
        FROM history AS h 
        JOIN user AS u ON u.id = h.user;');
        $historyDataList = $allHistory->fetchAll(\PDO::FETCH_ASSOC);

        return $this->hydrateHistoryList($historyDataList);
    }

    private function hydrateHistoryList($historyDataList): array {
        $history = [];

        foreach ($historyDataList as $historyDataRow) {
            $history[] = [
                    $historyDataRow['account'],
                    $historyDataRow['result'],
                    new User($historyDataRow['name'], $historyDataRow['id'])
            ];            
        }

        return $history;
    }
}
