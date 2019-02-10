<?php

namespace Library\Application\Handler;

use Library\Application\Command\ProlongateUserBookBorrowing;
use Library\Domain\ProlongationDays;
use Library\Domain\Repository\LibraryCardRepository;

class ProlongateUserBookBorrowingHandler
{
    /**
     * @var LibraryCardRepository
     */
    private $libraryCardRepository;

    /**
     * @var ProlongationDays
     */
    private $prolongationDays;

    public function __construct(LibraryCardRepository $libraryCardRepository, ProlongationDays $prolongationDays)
    {
        $this->libraryCardRepository = $libraryCardRepository;
        $this->prolongationDays = $prolongationDays;
    }

    public function handle(ProlongateUserBookBorrowing $command)
    {
        $libraryCard = $this->libraryCardRepository->findForUser($command->getUserEmail());

        $libraryCard->prolongate($command->getIsbnNumber(), $this->prolongationDays);
    }
}
