<?php

namespace Library\Infrastructure\Symfony\Controller;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Library\Application\Command\ProlongateUserBookBorrowing;
use Library\Application\Handler\ProlongateUserBookBorrowingHandler;
use Library\Domain\BookBorrowing;
use Library\Domain\LibraryCard;
use Library\Domain\ProlongationDays;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class LibraryController extends AbstractController
{
    /**
     * @Route("/library/prolongate", name="library_prolongate", methods={"POST"})
     */
    public function prolongate(
        Request $request,
        ProlongateUserBookBorrowingHandler $handler,
        ValidatorInterface $validator,
        ProlongationDays $prolongationDays,
        EntityManagerInterface $entityManager
    ) {

        $command = new ProlongateUserBookBorrowing(
            $request->get('userEmail'),
            $request->get('isbn')
        );
        $prolongationDays->configure($command->getIsbnNumber(), 7);

        $errors = $validator->validate($command);

        if (count($errors) === 0) {
            $handler->handle($command);

            $entityManager->flush();

            return $this->json(['status' => 'ok'], Response::HTTP_CREATED);
        }

        return $this->json($errors, Response::HTTP_BAD_REQUEST);
    }

    /**
     * @Route("/init", name="library_demo_init", methods={"POST"})
     */
    public function init(
        EntityManagerInterface $entityManager
    ) {
        $card = new LibraryCard('mail@mail.com');

        $card->addBorrowing(
            new BookBorrowing(
                'mail@mail.com',
                '9783161484100',
                new \DateTime('1-1-2020')
            )
        );

        $entityManager->persist($card);
        $entityManager->flush();

        return $this->json(['status' => 'ok'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/library/list", name="library_demo_list", methods={"GET"})
     */
    public function list(
        EntityManagerInterface $entityManager
    ) {
        /** @var LibraryCard[] $libraryCards */
        $libraryCards = $entityManager->getRepository(LibraryCard::class)->findAll();


        return $this->json(['status' => 'ok', 'libraryCards' => $libraryCards], Response::HTTP_OK);
    }

}
