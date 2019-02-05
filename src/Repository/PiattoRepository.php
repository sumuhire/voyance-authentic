<?php

namespace App\Repository;

use App\Entity\Piatto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Piatto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Piatto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Piatto[]    findAll()
 * @method Piatto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PiattoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Piatto::class);
    }

    // /**
    //  * @return Piatto[] Returns an array of Piatto objects
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
    public function findOneBySomeField($value): ?Piatto
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
