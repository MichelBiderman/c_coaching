<?php

namespace App\Repository;

use App\Entity\Actus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Actus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Actus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Actus[]    findAll()
 * @method Actus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ActusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Actus::class);
    }

    // /**
    //  * @return Actus[] Returns an array of Actus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Actus
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
