<?php
namespace Julio\Percentage\Domain\Model;

class Percentage {
    private string $history = '';

    public function calculatePercentageToNumber(int $mainNumber, int $percentage): int {
        $result = $mainNumber * ($percentage/100);
        $this->history += "$percentage\% of $mainNumber = $result | ";

        return $result;
    }

    public function calculateNumberToPercentage(int $mainNumber, int $number): int {
        $result = $number * ( 100 / $mainNumber);
        $this->history += "$number of $mainNumber = $result | ";

        return $result;
    }

    public function calculatePercentageIncrease(int $mainNumber, int $percentageIncrease): int {
        # todo
        return 0;
    }

    public function calculatePercentageDecrease(int $mainNumber, int $percentageDecrease): int {
        # todo
        return 0;
    }

    // public function saveHistory(User $user): bool {
    //     # todo
    //     return true;
    // }
}