<?php

namespace App\Repository;

use App\Entity\EventAnswer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\EventQuestion;  

/**
 * @method EventAnswer|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventAnswer|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventAnswer[]    findAll()
 * @method EventAnswer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<EventAnswer>
 */
class EventAnswerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventAnswer::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EventAnswer $entity, bool $flush = true): void
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
    public function remove(EventAnswer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return EventAnswer[] Returns an array of EventAnswer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EventAnswer
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    
    /**
     * @param EventQuestion $eventQuestion 
      * @return EventAnswers[] Returns an array of EventAnswers objects
     */
    public function getAnswers(EventQuestion $eventQuestion)
    {

        return $this->createQueryBuilder('e')
            ->innerJoin('e.eventQuestion', 'eq')   
            ->where('eq.id = :val')
            ->setParameter('val', $eventQuestion->getId())
            ->getQuery()
            ->getResult()
        ;


    }
    

}
