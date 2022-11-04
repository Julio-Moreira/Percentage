<?php
namespace julio\percentage\test\Domain\Repository;

use julio\percentage\Domain\Model\User;
use julio\percentage\Domain\Repository\PercentageRepository;
use PHPUnit\Framework\TestCase;

require_once "vendor/autoload.php";

class PercentageRepositoryTest extends TestCase {
    private static \PDO $conn;
    private PercentageRepository $percentageRepository;

    public static function setUpBeforeClass(): void {
        self::$conn = new \PDO('sqlite::memory:');

        self::$conn->exec('CREATE TABLE user (id INTEGER PRIMARY KEY, name TEXT, count INTEGER);');
        self::$conn->exec('CREATE TABLE history (account TEXT, result INTEGER, user TEXT, FOREIGN KEY (user) REFERENCES user(id));');

        self::$conn->exec("INSERT INTO user VALUES (1, 'Julio', 1)");
        self::$conn->exec("INSERT INTO user VALUES (2, 'Maria', 2)");

        self::$conn->exec("INSERT INTO history VALUES ('10 increased 100\%', 20, 1)");
        self::$conn->exec("INSERT INTO history VALUES ('20 increased 100\%', 40, 1)");
        
        self::$conn->exec("INSERT INTO history VALUES ('10 increased 100\%', 20, 2)");
        self::$conn->exec("INSERT INTO history VALUES ('20 increased 100\%', 40, 2)");
    }
    
    public function setUp(): void {
        $this->percentageRepository = new PercentageRepository(self::$conn);
    }

    public function testSaveHistoryOfUser() {
        $julio = new User('Julio', 1);
        $this->percentageRepository
                    ->saveHistory('10 increased 100\%', 20, $julio);
        $result = $julio->getAccountHistory();

        self::assertCount(7, $result);
    }

    public function testAllHistory() {
        $julio = new User('Julio', 1);
        $maria = new User('maria', 2);
        $this->percentageRepository
                    ->saveHistory('10 increased 100\%', 20, $julio);
        $this->percentageRepository
                    ->saveHistory('20 increased 100\%', 40, $julio);
        $this->percentageRepository
                    ->saveHistory('20 increased 100\%', 40, $maria);
        $julioHistory = $julio->getAccountHistory();
        $mariaHistory = $maria->getAccountHistory();
        $allHistory = $this->percentageRepository->allHistory();

        self::assertCount(7, $julioHistory);
        self::assertCount(0, $mariaHistory);

        self::assertCount(8, $allHistory);
        self::assertContainsOnly('array', $allHistory);
    }
}