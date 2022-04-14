<?php

namespace App\Repository;

use App\Entity\Offer;
use App\Entity\SavedOfferSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Offer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Offer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Offer[]    findAll()
 * @method Offer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Offer>
 */
class OfferRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Offer::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Offer $entity, bool $flush = true): void
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
    public function remove(Offer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Offer[] Returns an array of Offer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Offer
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param SavedOfferSearch $search
     * @return array<mixed>|float|int|string
     */
    public function getNumberOfNewOffers(SavedOfferSearch $search): array|float|int|string
    {
        $qb = $this->createQueryBuilder('o')
                   ->select('COUNT(o)')
                   ->andWhere('o.isValid = true')
            // we're only interested in the offers that were posted after the last search happened
                   ->andWhere('o.datePosted > :lastSearch')
                   ->setParameter(':lastSearch', $search->getLastSearch());

        /**
         * Here we test all the different criteria
         */
        if ($search->getTitle()) {
            $qb->andWhere('o.title LIKE :searchTitle')
                ->setParameter(':searchTitle', '*' . $search->getTitle() . '*');
        }

        if ($search->getCity()) {
            $qb->andWhere('o.city LIKE :searchCity')
                ->setParameter(':searchCity', $search->getTitle());
        }

        if ($search->getCompanyName()) {
            $qb->andWhere('o.companyName LIKE :searchCompany')
                ->setParameter(':searchCompany', $search->getCompanyName());
        }

        if ($search->getCountry()) {
            $qb->andWhere('o.country LIKE :searchCountry')
                ->setParameter(':searchCountry', $search->getCountry());
        }

        if ($search->getDescription()) {
            $qb->andWhere('o.description LIKE :searchDescription')
                ->setParameter(':searchDescription', '*' . $search->getDescription() . '*');
        }

        if ($search->getDomain()) {
            $qb->andWhere('o.domain = :searchDomain')
                ->setParameter(':searchDomain', $search->getDomain());
        }

        if ($search->getMaxSalary()) {
            $qb->andWhere('o.maxSalary <= :searchMaxSalary')
                ->setParameter(':searchMaxSalary', $search->getMaxSalary());
        }

        if ($search->getMinSalary()) {
            $qb->andWhere('o.minSalary >= :searchMinSalary')
                ->setParameter(':searchMinSalary', $search->getMinSalary());
        }

        if ($search->getTypeOfContract()) {
            $qb->andWhere('o.typeOfContract = :searchTypeOfContract')
                ->setParameter(':searchTypeOfContract', $search->getTypeOfContract());
        }

        //add any more conditions as needed

        return $qb->getQuery()->getScalarResult();
    }
}
