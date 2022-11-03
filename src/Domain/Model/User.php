<?php
namespace  julio\percentage\Domain\Model;

use julio\percentage\Domain\Repository\UserRepository;
use julio\percentage\Infra\Persistence\ConnectionCreator;

class User {
    private string $name, $id;
    private static $conn;
    private static $userRepository;

    public function __construct(string $name, int $id) {
        self::$conn = ConnectionCreator::createConnection();
        self::$userRepository = new UserRepository(self::$conn);
        
        $this->name = $name;
        $this->id = $id;
        if ( !self::$userRepository->userExists($id) ) {
            self::$userRepository->insertUser($name, $id);
        } 
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getAccountHistory() {
        return self::$userRepository->getHistoryOfUser($this);
    }
}