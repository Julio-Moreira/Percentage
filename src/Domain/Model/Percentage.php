<?php
namespace  julio\percentage\Domain\Model;

use julio\percentage\Domain\Repository\PercentageRepository;
use julio\percentage\Infra\Persistence\ConnectionCreator;

class Percentage {
    private string $history = '';
    
    /**
     * Calculate the percentage of a number
     * 
     * @return number of percentage
     */
    public function calculatePercentageToNumber(int $initialNumber, int $percentage): int {
        $result = $initialNumber * ( $percentage / 100 );
        $this->history .= "$percentage\% of $initialNumber = $result | ";

        return $result;
    }

    /**
     * Calculate the number of percentage
     * 
     * @return percentage of number
     */
    public function calculateNumberToPercentage(int $initialNumber, int $number): int {
        $result = $number * ( 100 / $initialNumber );
        $this->history .= "$number of $initialNumber = $result | ";

        return $result;
    }

    /**
     * Calculate the percentage increase of number 
     * 
     * @return number with percentage increase
     */
    public function calculatePercentageIncrease(int $initialNumber, int $percentageIncrease): int {
        $valueOfPercentage = $initialNumber * ( $percentageIncrease / 100 );
        $valueIncreased = $initialNumber + $valueOfPercentage;
        $this->history  .= "$initialNumber increased $percentageIncrease = $valueIncreased | ";

        return $valueIncreased;
    }

    /**
     * Calculate the percentage decrease of number 
     * 
     * @return number with percentage decrease
     */
    public function calculatePercentageDecrease(int $initialNumber, int $percentageDecrease): int {
        $valueOfPercentage = $initialNumber * ( $percentageDecrease / 100 );
        $valueDecreased = $initialNumber - $valueOfPercentage;
        $this->history  .= "$initialNumber decreased $percentageDecrease = $valueDecreased | ";

        return $valueDecreased;
    }

    public function getHistory() {
        return explode(' | ', $this->history);
    }

    public function saveHistory(User $user): bool {
        $percentageRepository = new PercentageRepository(ConnectionCreator::createConnection());
        $historyInArray = explode(' | ', $this->history);
        $accountInArray = array_map(fn($item) => explode(' = ', $item), $historyInArray);

        foreach ($accountInArray as $Account) {
            $percentageRepository->saveHistory($Account[0], $Account[1], $user);
        }

        return true;
    }
}