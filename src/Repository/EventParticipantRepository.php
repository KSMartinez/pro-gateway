<?php

namespace App\Repository;

use Doctrine\ORM\ORMException;
use App\Entity\EventParticipant;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\EventDispatcher\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method EventParticipant|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventParticipant|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventParticipant[]    findAll()
 * @method EventParticipant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<EventParticipant>
 */
class EventParticipantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventParticipant::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(EventParticipant $entity, bool $flush = true): void
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
    public function remove(EventParticipant $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


     /**
    * @return EventParticipant[] Returns an array of Event objects
     * @param ?int $event_id 
    */
    public function getParticipants(?int $event_id)
    {
        return $this->createQueryBuilder('e')      
            ->andWhere('e.event = :val')
            ->setParameter('val', $event_id)  
            ->getQuery()            
            ->getResult()                 
              
        ;   
    }


    
     /**
    * @return boolean // We check if the user is already registered 
    * @param ?int $user_id
    * @param ?int $event_id
    */
    public function userIsAlreadyRegistered(?int $user_id, ?int $event_id)
    {

        $q =  $this->createQueryBuilder('e')          
            ->where('e.user = :val1')
            ->andWhere('e.event = :val2')
            ->setParameter('val1', $user_id)  
            ->setParameter('val2', $event_id)  
            ->getQuery()                 
            ->getResult();     

        if( $q == NULL)
        return false;

        return true;  
        
    }

     
     /**
    * @return EventParticipant[] Returns an array of Event Participant 
     * @param ?int $user_id 
    */
    public function eventParticipants(?int $user_id)
    {

        return $this->createQueryBuilder('e')      
        ->where('e.user = :val')
        ->setParameter('val', $user_id)  
        ->getQuery()            
        ->getResult()                 
              
    ;      

    }


    
     /**
    * @return EventParticipant[] Returns an array of EventParticipant objects
     */
    public function getAll()
    {
        return $this->createQueryBuilder('e')
        ->getQuery()  
        ->getResult(); 
   
    }


    // /**
    //  * @return EventParticipant[] Returns an array of EventParticipant objects
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
    public function findOneBySomeField($value): ?EventParticipant
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */


   
   
}
