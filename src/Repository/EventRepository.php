<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\ORM\ORMException;
use App\Entity\EventParticipant;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Event>
 */
class EventRepository extends ServiceEntityRepository  
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Event $entity, bool $flush = true): void
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
    public function remove(Event $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Event[] Returns an array of Event objects
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
    public function findOneBySomeField($value): ?Event
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
    * @return Event[] Returns an array of Event objects
    */
    public function allEvents() 
    {
        return $this->createQueryBuilder('e')
            ->where('e.company IS NOT NULL')
            ->orWhere('e.university IS NOT NULL')   
            ->andWhere('e.createdAt IS NOT NULL')   
            ->getQuery()     
            ->getResult()              
              
        ;
    }


    
    /**
    * @return Event[] Returns an array of Event objects
    * @param array<mixed> $events 
     */
    public function userEvents(array $events = []) 
    {
    
        // We gonna take all the eventParticipant of the user and after check here with a whereIn(e.id, $eventsParticipantArray )
        return $this->createQueryBuilder('e')
        ->where('e.id IN (:ids)')
        ->setParameter('ids', $events) 
        ->getQuery()  
        ->getResult(); 

     
    }
    //  /**
    // * @return Event[] Returns an array of Event objects
    // */
    // public function onlyUniversities() 
    // {  
    //     return $this->createQueryBuilder('e')
    //         ->where('e.university IS NOT NULL')   
    //         ->andWhere('e.company IS NULL')
    //         ->getQuery()         
    //         ->getResult()          
           
    //     ;
    // }
}
