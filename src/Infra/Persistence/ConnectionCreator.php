<?php
namespace Julio\Percentage\Infra\Persistence;

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
// $conn->exec('CREATE TABLE historyNumberModify (account TEXT, result INTEGER, user TEXT, FOREIGN KEY (user) REFERENCES user(id));');
// $conn->exec('CREATE TABLE historyNumberBased (account TEXT, result INTEGER, user TEXT, FOREIGN KEY (user) REFERENCES user(id));');
// $conn->exec('CREATE TABLE user (id INTEGER PRIMARY KEY, name TEXT, count INTEGER);');