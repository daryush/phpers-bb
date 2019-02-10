<?php

namespace Library\Application\Command;


class ProlongateUserBookBorrowing
{
    private $userEmail;

    private $isbnNumber;

    public function __construct(string $userEmail, string $isbnNumber)
    {
        $this->userEmail = $userEmail;
        $this->isbnNumber = $isbnNumber;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getIsbnNumber(): string
    {
        return $this->isbnNumber;
    }
}
