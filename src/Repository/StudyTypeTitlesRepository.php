<?php

namespace App\Repository;

use App\Entity\StudyTypeTitles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StudyTypeTitles|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudyTypeTitles|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudyTypeTitles[]    findAll()
 * @method StudyTypeTitles[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudyTypeTitlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StudyTypeTitles::class);
    }

    // /**
    //  * @return StudyTypeTitles[] Returns an array of StudyTypeTitles objects
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
    public function findOneBySomeField($value): ?StudyTypeTitles
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
