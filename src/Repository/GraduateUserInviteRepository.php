<?php
namespace App\Repository;
use App\Entity\Issuance;
use App\Entity\GraduateUserInvite;
use App\DTO\GraduateUserInviteSearch;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
/**
 * @method GraduateUserInvite|null find($id, $lockMode = null, $lockVersion = null)
 * @method GraduateUserInvite|null findOneBy(array $criteria, array $orderBy = null)
 * @method GraduateUserInvite[]    findAll()
 * @method GraduateUserInvite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GraduateUserInviteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GraduateUserInvite::class);
    }
    /**
     * @return GraduateUserInvite[] Returns an array of GraduateUserInvite objects
     */
    public function findByEmail(GraduateUserInviteSearch $value)
    {
        $queryBuilder = $this->createQueryBuilder('invite');
        if (!empty($value->search)) {
            $queryBuilder->andWhere('invite.email like :val');
            $queryBuilder->setParameter('val', '%' . $value->getSearch() . '%');
        }
        return $queryBuilder->getQuery()->execute();
    }

    public function findAllByJoinedToIssuance($issuance){

        return $this->createQueryBuilder('g')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('g.issuance', 'i')
        ->addSelect('i')
        ->where('i.id = :val')
        ->setParameter('val', $issuance)
        ->orderBy('g.acceptanceDate', 'DESC')
        ->getQuery()
        ->getResult()
    ;

    }

    public function findAnsweredByJoinedToIssuance($issuance){

        return $this->createQueryBuilder('g')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('g.issuance', 'i')
        ->addSelect('i')
        ->where('i.id = :val')
        ->andWhere('g.acceptance IS NOT NULL')
        ->setParameter('val', $issuance)
        ->getQuery()
        ->getResult()
    ;

    }

    public function findAcceptedByJoinedToIssuance($issuance){

        return $this->createQueryBuilder('g')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('g.issuance', 'i')
        ->addSelect('i')
        ->where('i.id = :val')
        ->andWhere('g.acceptance = :accepted')
        ->setParameter('accepted', 'accepted')
        ->setParameter('val', $issuance)
        ->getQuery()
        ->getResult()
    ;

    }

    public function findDeclinedByJoinedToIssuance($issuance){

        return $this->createQueryBuilder('g')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('g.issuance', 'i')
        ->addSelect('i')
        ->where('i.id = :val')
        ->andWhere('g.acceptance = :accepted')
        ->setParameter('accepted', 'declined')
        ->setParameter('val', $issuance)
        ->getQuery()
        ->getResult()
    ;

    }

    public function findNullByJoinedToIssuance($issuance){

        return $this->createQueryBuilder('g')
        // ->innerJoin('g','issuance','i', 'g.issuance = :val')
        ->innerJoin('g.issuance', 'i')
        ->addSelect('i')
        ->where('i.id = :val')
        ->andWhere('g.acceptance IS NULL')
        ->setParameter('val', $issuance)
        ->getQuery()
        ->getResult()
    ;

    }


   

//    /**
//     * @return GraduateUserInvite[] Returns an array of GraduateUserInvite objects
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
    public function findOneBySomeField($value): ?GraduateUserInvite
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
