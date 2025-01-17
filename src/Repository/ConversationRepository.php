<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 * @extends ServiceEntityRepository<Conversation>
 *
 *
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Conversation $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @param Conversation $entity
     * @param bool         $flush
     */
    public function remove(Conversation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Conversation[] Returns an array of Conversation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Conversation
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    /**
     * @param User $user
     * @return Conversation[]
     */
    public function getConversationsOfUser(User $user): array
    {
        $qb = $this->createQueryBuilder('c')
                   ->innerJoin('c.users', 'u')
                   ->andWhere('u = :user')
                   ->setParameter(':user', $user)
                   ->getQuery();

        return $qb->getResult();
    }

    /**
     * @param User $user1
     * @param User $user2
     * @return Conversation|null
     * @throws NonUniqueResultException
     */
    public function getConversationBetweenUsers(User $user1, User $user2): ?Conversation
    {

        $qb = $this->createQueryBuilder('c')
                   ->innerJoin('c.users', 'u')
                   ->groupBy('c.id')
            // 2 because we have two users. This can be extended to multiple users if we have groups conversations.
                   ->having('COUNT(u) = 2');

        //user 1
        $qb->innerJoin('c.users', 'u' . $user1->getId())
            ->andWhere('u'.$user1->getId().'.id IN (:user1_id)')
            ->setParameter(':user1_id', $user1->getId());

        //user2
        $qb->innerJoin('c.users', 'u' . $user2->getId())
           ->andWhere('u'.$user2->getId().'.id IN (:user2_id)')
           ->setParameter(':user2_id', $user2->getId());

        /** @var Conversation|null $result */
        $result =  $qb->getQuery()->getOneOrNullResult();

        return $result;

    }
}
