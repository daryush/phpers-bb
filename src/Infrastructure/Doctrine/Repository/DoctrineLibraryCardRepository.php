<?php

namespace Library\Infrastructure\Doctrine\Repository;

use Doctrine\Common\Persistence\ObjectManager;
use Library\Domain\LibraryCard;
use Library\Domain\Repository\LibraryCardRepository;

class DoctrineLibraryCardRepository implements LibraryCardRepository
{
    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    public function findForUser(string $userEmail): ?LibraryCard
    {
        return $this->objectManager->getRepository(LibraryCard::class)->findOneBy([
            'userEmail' => $userEmail
        ]);
    }

    public function add(LibraryCard $libraryCard)
    {
        $this->objectManager->persist($libraryCard);
    }
}
