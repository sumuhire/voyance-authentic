<?php

namespace App\Repository;

use App\Entity\DegreeTitle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DegreeTitle|null find($id, $lockMode = null, $lockVersion = null)
 * @method DegreeTitle|null findOneBy(array $criteria, array $orderBy = null)
 * @method DegreeTitle[]    findAll()
 * @method DegreeTitle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DegreeTitleRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DegreeTitle::class);
    }

    public function findOneBySchoolAndDegreeCode($school, $degreeCode)
    {
        return $this->createQueryBuilder('d')
        ->where('d.school = :school')
        ->andWhere('d.degreeCode = :degreeCode')
        ->setParameter('school', $school)
        ->setParameter('degreeCode', $degreeCode)
        ->getQuery()
        ->getResult()
    ;

    }

//    /**
//     * @return DegreeTitle[] Returns an array of DegreeTitle objects
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
    public function findOneBySomeField($value): ?DegreeTitle
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
