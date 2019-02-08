<?php

namespace App\Repository;

use App\Entity\Secondi;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Secondi|null find($id, $lockMode = null, $lockVersion = null)
 * @method Secondi|null findOneBy(array $criteria, array $orderBy = null)
 * @method Secondi[]    findAll()
 * @method Secondi[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecondiRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Secondi::class);
    }

    // /**
    //  * @return Secondi[] Returns an array of Secondi objects
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
    public function findOneBySomeField($value): ?Secondi
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
