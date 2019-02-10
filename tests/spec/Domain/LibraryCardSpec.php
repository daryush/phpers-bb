<?php

namespace spec\Library\Domain;

use Library\Domain\LibraryCard;
use Library\Domain\BookBorrowing;
use Library\Domain\ProlongationDays;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class LibraryCardSpec extends ObjectBehavior
{
    function it_should_prolongate_book_borrowing_for_specific_isbn(
      ProlongationDays $prolongationDays
    ) {
        $isbnNumber = '1234567';
        $userEmail = 'mail@test.com';
        $bookBorrowing = new BookBorrowing($userEmail, $isbnNumber, new \DateTime('1-1-2020'));
        $this->beConstructedWith($userEmail);

        $this->addBorrowing($bookBorrowing);

        $prolongationDays->getForIsbn($isbnNumber)->willReturn(7);

        $this->prolongate($isbnNumber, $prolongationDays);

        $this->getBorrowing($isbnNumber)->shouldBeLike(
            new BookBorrowing($userEmail, $isbnNumber, new \DateTime('8-1-2020'))
        );
    }
}
