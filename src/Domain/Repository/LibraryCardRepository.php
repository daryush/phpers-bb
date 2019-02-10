<?php

namespace Library\Domain\Repository;

use Library\Domain\LibraryCard;

interface LibraryCardRepository
{
    public function findForUser(string $userEmail): ?LibraryCard;

    public function add(LibraryCard $libraryCard);
}
