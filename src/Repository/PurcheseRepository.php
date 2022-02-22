<?php

namespace App\Repository;

use App\Entity\Purchese;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Purchese|null find($id, $lockMode = null, $lockVersion = null)
 * @method Purchese|null findOneBy(array $criteria, array $orderBy = null)
 * @method Purchese[]    findAll()
 * @method Purchese[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PurcheseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Purchese::class);
    }

    // /**
    //  * @return Purchese[] Returns an array of Purchese objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Purchese
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
