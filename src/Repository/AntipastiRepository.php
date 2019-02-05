<?php

namespace App\Repository;

use App\Entity\Antipasti;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Antipasti|null find($id, $lockMode = null, $lockVersion = null)
 * @method Antipasti|null findOneBy(array $criteria, array $orderBy = null)
 * @method Antipasti[]    findAll()
 * @method Antipasti[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AntipastiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Antipasti::class);
    }

    // /**
    //  * @return Antipasti[] Returns an array of Antipasti objects
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
    public function findOneBySomeField($value): ?Antipasti
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
