<?php

namespace App\Repository;

use App\Entity\Faculty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Faculty|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faculty|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faculty[]    findAll()
 * @method Faculty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacultyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Faculty::class);
    }

    
    /**
    *   @return Faculty[] Returns an array of Faculty objects
    */
    
    public function findBySchoolUser($schoolUser)
    {
        return $this->createQueryBuilder('q')
            ->andWhere(':val MEMBER OF q.schoolUsers')
            ->setParameter('val', $schoolUser)
            ->getQuery()
            ->getResult()

           
        ;
    }

    public function findBySchool($school)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.school = :val')
            ->setParameter('val', $school)
            ->getQuery()
            ->getResult()

           
        ;
    }

    // SELECT f.id FROM Faculty f WHERE :schoolUserId MEMBER OF f.schoolUsers

    public function findByQuestionDate($userId)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.user = :val')
            ->setParameter('val', $userId)
            ->orderBy('q.creationDate', 'DESC')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    

    /*
    public function findOneBySomeField($value): ?Faculty
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
