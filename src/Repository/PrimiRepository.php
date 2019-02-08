<?php

namespace App\Repository;

use App\Entity\Primi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Primi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Primi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Primi[]    findAll()
 * @method Primi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PrimiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Primi::class);
    }

    // /**
    //  * @return Primi[] Returns an array of Primi objects
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
    public function findOneBySomeField($value): ?Primi
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
