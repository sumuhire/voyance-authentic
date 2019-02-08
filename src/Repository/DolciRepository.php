<?php

namespace App\Repository;

use App\Entity\Dolci;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Dolci|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dolci|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dolci[]    findAll()
 * @method Dolci[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DolciRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Dolci::class);
    }

    // /**
    //  * @return Dolci[] Returns an array of Dolci objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Dolci
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
