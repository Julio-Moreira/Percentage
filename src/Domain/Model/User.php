<?php
namespace Julio\Percentage\Domain\Model;

class User {
    private string $name, $id;

    public function __construct(string $name, int $id) {
        $this->name = $name;
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function getId() {
        return $this->id;
    }

    public function getAccountHistory() {
        # code...
    }

    public function getHistoryType() {
        # code...
    }
}