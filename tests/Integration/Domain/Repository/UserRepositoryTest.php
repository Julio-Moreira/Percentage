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
    }

    public function testGetAllUsers() {
        $allUsers = $this->userRepository->selectAllUsers();

        self::assertCount(2, $allUsers);
        self::assertContainsOnly('array', $allUsers);

        self::assertSame(['id' => "1", 'name' => "Julio", 'count' => "1"], $allUsers[0]);
        self::assertSame(['id' => "2", 'name' => "Maria", 'count' => "2"], $allUsers[1]);
    }
    
    /** @dataProvider UsersProvider */
    public function testUserExist($julioUser) {
        $userThatNotExist = new User('Sla', 4, self::$conn);        

        $resultTrue = $this->userRepository
                           ->userExists($julioUser->getId());
        $resultFalse = $this->userRepository
                            ->userExists($userThatNotExist->getId());

        self::assertTrue($resultTrue);
        self::assertFalse($resultFalse);
    }

    /** @dataProvider UsersProvider */
    public function testUserHistory($julioUser, $mariaUser) {
        $historyOfJulio = $this->userRepository->getHistoryOfUser($julioUser);
        $historyOfMaria = $this->userRepository->getHistoryOfUser($mariaUser);

        self::assertCount(2, $historyOfJulio);
        self::assertContainsOnly('array', $historyOfJulio);
        self::assertSame(['10 increased 100\% = 20', '1'], $historyOfJulio[0]);
        
        self::assertCount(2, $historyOfMaria);
        self::assertContainsOnly('array', $historyOfMaria);
        self::assertSame(['20 increased 100\% = 40', '2'], $historyOfMaria[1]);
    }    

    public function testInsertUser() {
        $newUser = new User('João', 3);
        $this->userRepository->insertUser(
            $newUser->getName(), 
            $newUser->getId()
        );
        $users = $this->userRepository->selectAllUsers();
        
        self::assertTrue($this->userRepository
                                        ->userExists($newUser->getId()));
        self::assertContainsOnly('string', $users[2]);
        self::assertCount(3, $users[2]);
        self::assertSame(['id' => '3', 'name' => 'João', 'count' => '0'], $users[2]);  
    }

    public function UsersProvider() {
        return [
            [ new User('Julio', 1),
            new User('Maria', 2) ]
        ];
    }
}