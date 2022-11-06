<?php
namespace julio\percentage\test\Domain\Model;

use julio\percentage\Domain\Model\Percentage;
use julio\percentage\Domain\Model\User;
use PHPUnit\Framework\TestCase;

require_once "vendor/autoload.php";

class PercentageTest extends TestCase {
    public function testPercentageDecrease() {
        $percentageCalculator = new Percentage();
        $result = $percentageCalculator->calculatePercentageDecrease(50, 50);

        self::assertSame(25, $result);
    }

    public function testPercentageIncrease() {
        $percentageCalculator = new Percentage();
        $result = $percentageCalculator->calculatePercentageIncrease(50, 100);

        self::assertSame(100, $result);
    }

    public function testPercentageToNumber() {
        $percentage = new Percentage();
        $result = $percentage->calculatePercentageToNumber(100, 25);

        self::assertSame(25, $result);
    }
    
    public function testNumberToPercentage() {
        $percentage = new Percentage();
        $result = $percentage->calculateNumberToPercentage(1000, 10);

        self::assertSame(1, $result);
    }

    public function testHistory() {
        $percentage = new Percentage();
        $percentage->calculateNumberToPercentage(40, 10);
        $percentage->calculatePercentageDecrease(50, 100);
        $history = $percentage->getHistory();

        self::assertCount(3, $history);
        self::assertContainsOnly('string', $history);
        self::assertSame('10 of 40 = 25', $history[0]);
        self::assertSame('50 decreased 100 = 0', $history[1]);
    }
}