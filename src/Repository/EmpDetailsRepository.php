<?php

namespace App\Repository;

use App\Entity\EmpDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method EmpDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method EmpDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method EmpDetails[]    findAll()
 * @method EmpDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmpDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EmpDetails::class);
    }

    // /**
    //  * @return EmpDetails[] Returns an array of EmpDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?EmpDetails
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
