<?php

namespace App\Repository;

use App\Entity\GraduateUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method GraduateUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method GraduateUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method GraduateUser[]    findAll()
 * @method GraduateUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GraduateUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GraduateUser::class);
    }

    public function findByIssuance($value)
    {
        $qb = $this->createQueryBuilder('s')
        ->select('s')
        ->join('s.issuances','c')
        ->where('c.issuance = :issuance')
        ->setParameter('issuance', $value)
        ->orderBy('c.creationDate', 'ASC')
        ->getQuery()
        ->getResult()
        
        ;
    }

    public function findBySchool($value)
    {
        $qb = $this->createQueryBuilder('s')
        ->select('s')
        ->join('s.school','c')
        ->where('c.school = :school')
        ->setParameter('school', $value)
        // ->orderBy('c.creationDate', 'ASC')
        ->getQuery()
        ->getResult()
        
        ;
    }

    public function findGradBySchool($school){

        return $this->createQueryBuilder('g')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('g.school', 's')
        ->addSelect('s')
        ->where('s.id = :val')
        ->setParameter('val', $school)
        ->getQuery()
        ->getResult()
    ;

    }


    


//    /**
//     * @return GraduateUser[] Returns an array of GraduateUser objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?GraduateUser
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
