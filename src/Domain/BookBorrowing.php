<?php

namespace Library\Domain;

class BookBorrowing
{
    private $userEmail;

    private $bookIsbn;

    private $endDate;

    public function __construct(string $userEmail, string $bookIsbn, \DateTime $endDate)
    {
        $this->userEmail = $userEmail;
        $this->bookIsbn = $bookIsbn;
        $this->endDate = $endDate;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function getBookIsbn(): string
    {
        return $this->bookIsbn;
    }

    public function getEndDate(): \DateTime
    {
        return $this->endDate;
    }
}
