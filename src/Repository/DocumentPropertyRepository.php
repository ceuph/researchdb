<?php

namespace App\Repository;

use App\Entity\DocumentProperty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DocumentProperty|null find($id, $lockMode = null, $lockVersion = null)
 * @method DocumentProperty|null findOneBy(array $criteria, array $orderBy = null)
 * @method DocumentProperty[]    findAll()
 * @method DocumentProperty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentPropertyRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DocumentProperty::class);
    }

    // /**
    //  * @return DocumentProperty[] Returns an array of DocumentProperty objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DocumentProperty
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
