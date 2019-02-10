<?php

namespace Library\Domain;

class LibraryCard
{
    private $userEmail;

    /**
     * @var BookBorrowing[]
     */
    private $borrowings;

    public function __construct(string $userEmail)
    {
        $this->userEmail = $userEmail;
    }

    public function getUserEmail(): string
    {
        return $this->userEmail;
    }

    public function addBorrowing(BookBorrowing $bookBorrowing)
    {
        $this->borrowings[$bookBorrowing->getBookIsbn()] = $bookBorrowing;
    }

    public function prolongate(string $isbnNumber, ProlongationDays $prolongationDays)
    {
        $this->borrowings[$isbnNumber] = new BookBorrowing(
            $this->borrowings[$isbnNumber]->getUserEmail(),
            $isbnNumber,
            $this->borrowings[$isbnNumber]->getEndDate()->add(
                new \DateInterval(
                    sprintf(
                        'P%sD',
                        $prolongationDays->getForIsbn($isbnNumber)
                    )
                )
            )
        );
    }

    public function getBorrowing(string $isbnNumber): BookBorrowing
    {
        return $this->borrowings[$isbnNumber];
    }
}
