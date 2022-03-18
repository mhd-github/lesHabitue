<?php

namespace App\Repository;

use App\Entity\Portefeuille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Portefeuille|null find($id, $lockMode = null, $lockVersion = null)
 * @method Portefeuille|null findOneBy(array $criteria, array $orderBy = null)
 * @method Portefeuille[]    findAll()
 * @method Portefeuille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PortefeuilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Portefeuille::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Portefeuille $entity, bool $flush = true): void
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
    public function remove(Portefeuille $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Portefeuille[] Returns an array of Portefeuille objects
     * Recuperer les portefeuilles d'un user 
     */

    public function findByUser($value): array
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'c')
            ->join('u.commerce', 'c')
            ->andWhere('u.user = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Portefeuille Return an Portefeuille object
     * Recuperer un portefeuille avec un user et un commerce
     */

    public function findByUserAndCommerce($user, $commerce): Portefeuille
    {
        return $this->createQueryBuilder('u')
            ->select('u', 'c')
            ->join('u.commerce', 'c')
            ->andWhere('u.user = ' . $user->getId())
            ->andWhere('c.id = ' . $commerce->getId())
            ->getQuery()
            ->getOneOrNullResult();
    }


    /*
    public function findOneBySomeField($value): ?Portefeuille
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
