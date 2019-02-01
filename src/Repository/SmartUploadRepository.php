<?php

namespace App\Repository;

use App\Entity\SmartUpload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SmartUpload|null find($id, $lockMode = null, $lockVersion = null)
 * @method SmartUpload|null findOneBy(array $criteria, array $orderBy = null)
 * @method SmartUpload[]    findAll()
 * @method SmartUpload[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SmartUploadRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SmartUpload::class);
    }

//    /**
//     * @return SmartUpload[] Returns an array of SmartUpload objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SmartUpload
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
