<?php

namespace App\Repository;

use App\Entity\Studies;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Studies|null find($id, $lockMode = null, $lockVersion = null)
 * @method Studies|null findOneBy(array $criteria, array $orderBy = null)
 * @method Studies[]    findAll()
 * @method Studies[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudiesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Studies::class);
    }

    // /**
    //  * @return Studies[] Returns an array of Studies objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Studies
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
