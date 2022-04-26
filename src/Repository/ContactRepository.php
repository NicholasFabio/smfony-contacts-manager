<?php

namespace App\Repository;

use App\Entity\Contact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contact|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contact|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contact[]    findAll()
 * @method Contact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContactRepository extends ServiceEntityRepository
{   
    private $manager;

    public function __construct(ManagerRegistry $registry)
    {   
        $this->manager = $registry;
        parent::__construct($registry, Contact::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Contact $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Contact $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function saveContact($name, $email, $gender, $content)
    {
        $newContact = new Contact();

        $newContact
            ->setName($name)
            ->setGender($gender)
            ->setEmail($email)
            ->setContent($content);

        $this->_em->persist($newContact);
        $this->_em->flush();
    }

    public function updateContact(Contact $contact): Contact
    {
        $this->_em->persist($contact);
        $this->_em->flush();

        return $contact;
    }

    public function removeContact(Contact $contact)
    {
        $this->_em->remove($contact);
        $this->_em->flush();
    }
    
    public function findContact(int $id): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c.id,c.name,c.email,c.gender,c.content
            FROM App\Entity\Contact c
            WHERE c.id = :id'
        )->setParameter('id', $id);

        // returns an array of Contact objects
        return $query->getResult();
    }

    public function getContacts(): array{
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT c.id,c.name,c.email,c.gender,c.content
            FROM App\Entity\Contact c
            ORDER BY c.id ASC'
        );

        // returns an array of Contact objects
        return $query->getResult();
    }
}
