<?php

namespace App\Repository;

use App\Entity\DegreeField;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DegreeField|null find($id, $lockMode = null, $lockVersion = null)
 * @method DegreeField|null findOneBy(array $criteria, array $orderBy = null)
 * @method DegreeField[]    findAll()
 * @method DegreeField[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DegreeFieldRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DegreeField::class);
    }

//    /**
//     * @return DegreeField[] Returns an array of DegreeField objects
//     */
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
    public function findOneBySomeField($value): ?DegreeField
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
