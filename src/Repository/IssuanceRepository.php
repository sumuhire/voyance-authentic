<?php

namespace App\Repository;

use App\Entity\Issuance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Issuance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issuance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issuance[]    findAll()
 * @method Issuance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssuanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Issuance::class);
    }

    public function findByGrad($graduateUser){

        return $this->createQueryBuilder('i')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('i.graduateUsers', 'g')
        ->addSelect('g')
        ->where('g.id = :val')
        ->setParameter('val', $graduateUser)
        ->getQuery()
        ->getResult()
    ;

    }

    public function findBySchool($school){

        return $this->createQueryBuilder('i')
        ->innerJoin('i.school', 's')
        ->addSelect('s')
        ->where('s.id = :val')
        ->setParameter('val', $school)
        ->orderBy('i.classYearEnd', 'DESC')
        ->getQuery()
        ->getResult()
    ;

    }

    

//    /**
//     * @return Issuance[] Returns an array of Issuance objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Issuance
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
