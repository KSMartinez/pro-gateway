<?php

namespace App\Repository;

use App\Entity\Experience;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(User $entity, bool $flush = true): void
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
    public function remove(User $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    
    /**
    * @return User[] Returns an array of User objects
    * @param array<mixed> $participants  
     */
    public function userEvents(array $participants = [])
    {
    
        // We gonna take all the eventParticipant of the user and after check here with a whereIn(e.id, $eventsParticipantArray )
        return $this->createQueryBuilder('u')
        ->where('u.id IN (:ids)')
        ->setParameter('ids', $participants) 
        ->getQuery()    
        ->getResult();    

     
    }

       

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

     
    /**
     * @return array<User>  
     */ 
    public function annuaireList(): Array   
    {
        return $this->createQueryBuilder('u')
            // ->innerJoin(CV::class,'cv', Join::WITH, 'u=cv.user')
            // ->innerJoin(Experience::class, 'e', Join::WITH, 'cv=e.cV')
            ->andWhere('u.charteSigned = :val')
            ->setParameter('val', true)
            ->andWhere('u.datasVisibleForAnnuaire = :value')
            ->setParameter('value', true)
            ->orderBy('u.surname', 'ASC')   
            ->getQuery()         
            ->getResult()
        ;  

        
    }
    
}
