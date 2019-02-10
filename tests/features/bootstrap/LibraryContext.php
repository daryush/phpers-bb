<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;

use Library\Application\Handler\ProlongateUserBookBorrowingHandler;
use Library\Domain\LibraryCard;
use Library\Domain\BookBorrowing;
use Library\Domain\ProlongationDays;
use Library\Domain\Repository\LibraryCardRepository;

use Library\Application\Command\ProlongateUserBookBorrowing;

use Library\Infrastructure\InMemory\Repository\InMemoryLibraryCardRepository;

class LibraryContext implements Context
{
    private $bookBorrowingUser;
    private $bookBorrowingIsbn;

    /**
     * @var LibraryCardRepository
     */
    private $libraryCardRepository;

    public function __construct()
    {
        $this->libraryCardRepository = new InMemoryLibraryCardRepository();
        $this->prolongationDays = new ProlongationDays();
    }

    /**
     * @Given there is library user :userEmail
     */
    public function thereIsLibraryUser($userEmail)
    {
        $this->libraryCardRepository->add(new LibraryCard($userEmail));
    }

    /**
     * @Given :userEmail borrowed book :bookTitle with ISBN number :bookIsbn
     */
    public function borrowedBookWithIsbnNumber($userEmail, $bookTitle, $bookIsbn)
    {
        $this->bookBorrowingUser = $userEmail;
        $this->bookBorrowingIsbn = $bookIsbn;
    }

    /**
     * @Given borrowing period of book with ISBN number :bookIsbn by :userEmail ends in :borrowingEndDate
     */
    public function borrowingPeriodOfBookWithIsbnNumberByEndsIn($bookIsbn, $userEmail, $borrowingEndDate)
    {
        $bookBorrowing = new BookBorrowing(
            $this->bookBorrowingUser,
            $this->bookBorrowingIsbn,
            new \DateTime($borrowingEndDate)
        );

        $userLibraryCard = $this->libraryCardRepository->findForUser($userEmail);

        $userLibraryCard->addBorrowing($bookBorrowing);
    }

    /**
     * @Given borrowing period for book with ISBN number :isbnNumber can be prolonged by :prolontationDaysNumber days
     */
    public function borrowingPeriodForBookWithIsbnNumberCanBeProlongedByDays($isbnNumber, $prolontationDaysNumber)
    {
        $this->prolongationDays->configure($isbnNumber, $prolontationDaysNumber);
    }

    /**
     * @When :userEmail prolong his borrowing of book with ISBN :isbnNumber
     */
    public function prolongHisBorrowingOfBookWithIsbn($userEmail, $isbnNumber)
    {
        $command = new ProlongateUserBookBorrowing($userEmail, $isbnNumber);

        $handler = new ProlongateUserBookBorrowingHandler(
            $this->libraryCardRepository,
            $this->prolongationDays
        );

        $handler->handle($command);
    }

    /**
     * @Then borrowing period of book with ISBN number :isbnNumber by :userEmail should ends in :expectedEndDate
     */
    public function borrowingPeriodOfBookWithIsbnNumberByShouldEndsIn($isbnNumber, $userEmail, $expectedEndDate)
    {
        $libraryCard = $this->libraryCardRepository->findForUser($userEmail);

        $endDate = $libraryCard->getBorrowing($isbnNumber)->getEndDate();

        if ($endDate != new \DateTime($expectedEndDate)) {
            throw new \LogicException(
                sprintf(
                    'End date should be %s, but %s found',
                    $expectedEndDate,
                    $endDate->format('d-m-Y')
                )
            );
        }
    }
}
