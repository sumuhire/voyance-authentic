<?php

namespace App\Repository;

use App\Entity\Formaggio;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Formaggio|null find($id, $lockMode = null, $lockVersion = null)
 * @method Formaggio|null findOneBy(array $criteria, array $orderBy = null)
 * @method Formaggio[]    findAll()
 * @method Formaggio[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormaggioRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Formaggio::class);
    }

    // /**
    //  * @return Formaggio[] Returns an array of Formaggio objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Formaggio
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
