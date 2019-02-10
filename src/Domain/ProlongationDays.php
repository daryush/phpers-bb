<?php

namespace Library\Domain;

class ProlongationDays
{
    private $configuration;

    public function configure(string $isbnNumber, int $prolongationDaysNumber)
    {
        $this->configuration[$isbnNumber] = $prolongationDaysNumber;
    }

    public function getForIsbn(string $isbnNumber): int
    {
        return $this->configuration[$isbnNumber];
    }
}
