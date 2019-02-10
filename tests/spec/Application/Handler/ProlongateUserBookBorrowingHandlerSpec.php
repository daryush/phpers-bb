<?php

namespace spec\Library\Application\Handler;

use Library\Application\Command\ProlongateUserBookBorrowing;
use Library\Application\Handler\ProlongateUserBookBorrowingHandler;
use Library\Domain\LibraryCard;
use Library\Domain\ProlongationDays;
use Library\Domain\Repository\LibraryCardRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProlongateUserBookBorrowingHandlerSpec extends ObjectBehavior
{
    function it_handler_user_book_borrowing_prolongation(
        LibraryCardRepository $libraryCardRepository,
        LibraryCard $libraryCard,
        ProlongationDays $prolongationDays
    ) {
        $this->beConstructedWith($libraryCardRepository, $prolongationDays);

        $userEmail = 'test@mail.com';
        $isbnNumber = '123456789';
        $command = new ProlongateUserBookBorrowing($userEmail, $isbnNumber);

        $libraryCardRepository->findForUser($userEmail)->willReturn($libraryCard);

        $libraryCard->prolongate($isbnNumber, $prolongationDays)->shouldBeCalled();

        $this->handle($command);
    }
}
