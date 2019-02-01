<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\SchoolUser;
use App\DTO\SchoolUserSearch;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method SchoolUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method SchoolUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method SchoolUser[]    findAll()
 * @method SchoolUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SchoolUserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SchoolUser::class);
    }


    public function findById(SchoolUserSearch $value)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        if (!empty($value->search)) {
            $queryBuilder->andWhere('u.id like :val');
            $queryBuilder->setParameter('val', '%' . $value->getSearch() . '%');
        }
        
        return $queryBuilder->getQuery()->execute();
    }
    

    public function findSchoolUserBySchool($school){

        return $this->createQueryBuilder('s')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('s.school', 'sc')
        ->addSelect('sc')
        ->where('sc.id = :val')
        ->setParameter('val', $school)
        ->getQuery()
        ->getResult()
    ;

    }

    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

//    /**
//     * @return SchoolUser[] Returns an array of SchoolUser objects
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
    public function findOneBySomeField($value): ?SchoolUser
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
