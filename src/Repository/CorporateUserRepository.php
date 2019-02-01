<?php

namespace App\Repository;

use App\Entity\CorporateUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CorporateUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method CorporateUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method CorporateUser[]    findAll()
 * @method CorporateUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorporateUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CorporateUser::class);
    }

//    /**
//     * @return CorporateUser[] Returns an array of CorporateUser objects
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
    public function findOneBySomeField($value): ?CorporateUser
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
