<?php

namespace App\Repository;

use App\Entity\User;
use App\DTO\UserSearch;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements UserLoaderInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }
   

   /**
    * @return User[] Returns an array of User objects
   */
    
    public function findByEmail(UserSearch $value)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        if (!empty($value->search)) {

            $queryBuilder->andWhere('u.email like :val');
            $queryBuilder->setParameter('val', '%' . $value->getSearch() . '%');
        }
        

        return $queryBuilder->getQuery()->execute();

    }

    public function findById(UserSearch $value)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        if (!empty($value->search)) {

            $queryBuilder->andWhere('u.id like :val');
            $queryBuilder->setParameter('val', '%' . $value->getSearch() . '%');
        }
        

        return $queryBuilder->getQuery()->execute();

    }

    public function findByUsername($value)
    {
        $name = $this->createQueryBuilder('u')
            ->andWhere('u.username like :val')
            ->setParameter('val', '%' . $value->getSearch() . '%')
        ;
        return $name->getQuery()->execute();
    }

    public function findByName($value, $value2)
    {
            $name = $this->createQueryBuilder('u')
            ->andWhere('u.firstname like :val')
            ->andWhere('u.lastname like :val2')
            ->setParameter('val',$value->getSearch())
            ->setParameter('val2', $value2->getSearch())
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
        ;
        return $name->execute();
    }

    public function findByFirstName($value) {

        $name = $this->createQueryBuilder('u')
            ->andWhere('u.firstname like :val')
            ->setParameter('val', '%' . $value->getSearch() . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery();
        return $name->execute();
    }

    public function findByLastName($value) {

        $name = $this->createQueryBuilder('u')
            ->andWhere('u.lastname like :val')
            ->setParameter('val', '%' . $value->getSearch() . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery();
        return $name->execute();
    }

    public function findByDeparment($value) {

        $name = $this->createQueryBuilder('u')
            ->andWhere('u.department like :val')
            ->setParameter('val', '%' . $value->getSearch() . '%')
            ->orderBy('u.id', 'ASC')
            ->getQuery();
        return $name->execute();
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


    


    /*
    public function findOneBySomeField($value): ?User
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
