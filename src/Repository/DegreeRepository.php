<?php

namespace App\Repository;

use App\Entity\Degree;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Degree|null find($id, $lockMode = null, $lockVersion = null)
 * @method Degree|null findOneBy(array $criteria, array $orderBy = null)
 * @method Degree[]    findAll()
 * @method Degree[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DegreeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Degree::class);
    }


    /**
     * @return Degree[] Returns an array of Degree objects
     */
    
    public function findByGradAndYearEndOrder($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.graduateUser = :val')
            ->setParameter('val', $value)
            ->orderBy('d.classYearEnd', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Degree
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
