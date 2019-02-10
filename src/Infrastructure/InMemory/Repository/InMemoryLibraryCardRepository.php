<?php

namespace Library\Infrastructure\InMemory\Repository;

use Library\Domain\LibraryCard;
use Library\Domain\Repository\LibraryCardRepository;

class InMemoryLibraryCardRepository implements LibraryCardRepository
{
    public function findForUser(string $userEmail): ?LibraryCard
    {
        if (array_key_exists($userEmail, $this->cards)) {
            return $this->cards[$userEmail];
        }
    }

    public function add(LibraryCard $libraryCard)
    {
        $this->cards[$libraryCard->getUserEmail()] = $libraryCard;
    }
}
