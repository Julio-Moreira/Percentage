<?php
namespace  julio\percentage\Infra\Persistence;

use PDO;

class ConnectionCreator {

    /**
     * Create one connection with PDO
     * 
     * @return the PDO connection
     */
    public static function createConnection(): PDO {
    $path = __DIR__ . '/../../../data.db';
    $connection = new PDO("sqlite:$path");
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    return $connection;
    }
}

// $conn = ConnectionCreator::createConnection();
// $conn->exec('CREATE TABLE user (id INTEGER PRIMARY KEY, name TEXT, count INTEGER);');
// $conn->exec('CREATE TABLE history (account TEXT, result INTEGER, user TEXT, FOREIGN KEY (user) REFERENCES user(id));');
// $conn->exec("INSERT INTO user VALUES (1, 'Julio', 2)");
