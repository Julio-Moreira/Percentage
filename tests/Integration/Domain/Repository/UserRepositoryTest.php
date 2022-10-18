<?php
namespace julio\percentage\test\Domain\Repository;

use PDO;
use PHPUnit\Framework\TestCase;
use julio\percentage\Domain\Model\User;
use julio\percentage\Domain\Repository\UserRepository;
require_once "vendor/autoload.php";

class UserRepositoryTest extends TestCase {
    private static \PDO $conn;
    private UserRepository $userRepository;
    private user $julioUser;
    private user $mariaUser;

    public static function setUpBeforeClass(): void {
        self::$conn = new PDO('sqlite::memory:');

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
        $this->userRepository = new UserRepository(self::$conn);
        $this->julioUser = new User('Julio', 1);
        $this->mariaUser = new User('Maria', 2);
    }

    public function testGetAllUsers() {
        $allUsers = $this->userRepository->selectAllUsers();

        self::assertCount(2, $allUsers);
        self::assertContainsOnly('array', $allUsers);

        self::assertSame(['id' => "1", 'name' => "Julio", 'count' => "1"], $allUsers[0]);
        self::assertSame(['id' => "2", 'name' => "Maria", 'count' => "2"], $allUsers[1]);
    }
    
    public function testUserExist() {
        $userThatNotExist = new User('Sla', 3);        

        $resultTrue = $this->userRepository
                           ->userExists($this->julioUser);
        $resultFalse = $this->userRepository
                            ->userExists($userThatNotExist);

        self::assertTrue($resultTrue);
        self::assertFalse($resultFalse);
    }

    public function testUserHistory() {
        $historyOfJulio = $this->userRepository->getHistoryByUser($this->julioUser);
        $historyOfMaria = $this->userRepository->getHistoryByUser($this->mariaUser);
        var_dump($historyOfJulio);

        self::assertCount(2, $historyOfJulio);
        self::assertContainsOnly('array', $historyOfJulio);
        self::assertSame(['account' => '10 increased 100\%', 'result' => "20", 'user' => '1'], $historyOfJulio[0]);
        
        self::assertCount(2, $historyOfMaria);
        self::assertContainsOnly('array', $historyOfMaria);
        self::assertSame(['account' => '20 increased 100\%', 'result' => "40", 'user' => '2'], $historyOfMaria[1]);
    }
    
}