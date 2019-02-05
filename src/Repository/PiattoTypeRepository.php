<?php

namespace App\Repository;

use App\Entity\PiattoType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method PiattoType|null find($id, $lockMode = null, $lockVersion = null)
 * @method PiattoType|null findOneBy(array $criteria, array $orderBy = null)
 * @method PiattoType[]    findAll()
 * @method PiattoType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PiattoTypeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, PiattoType::class);
    }

    // /**
    //  * @return PiattoType[] Returns an array of PiattoType objects
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
    public function findOneBySomeField($value): ?PiattoType
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
