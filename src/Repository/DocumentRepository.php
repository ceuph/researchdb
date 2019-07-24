<?php

namespace App\Repository;

use App\Entity\Document;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Document|null find($id, $lockMode = null, $lockVersion = null)
 * @method Document|null findOneBy(array $criteria, array $orderBy = null)
 * @method Document[]    findAll()
 * @method Document[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DocumentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Document::class);
    }

    public function findByKeywords($keywords, $property = null, $value = '', $authors = '')
    {
        $params = explode(' ', trim($keywords));
        $qb = $this->createQueryBuilder('doc');
        if ($property) {
            $qb->innerJoin('doc.documentProperties', 'prop');
            $qb->andWhere($qb->expr()->eq('prop.name', ':prop'));
            $qb->andWhere($qb->expr()->like('prop.value', $qb->expr()->literal("%$value%")));
            $qb->setParameter('prop', $property);
        }
        if (strlen(trim($authors)) > 0) {
            $qb->innerJoin('doc.documentAuthors', 'author');
            $authors = explode(';', $authors);
            foreach ($authors as $author) {
                $details = explode(' ', $author);
                foreach ($details as $detail) {
                    if (strlen(trim($detail)))
                        $qb->orWhere($qb->expr()->like('author.displayName', $qb->expr()->literal("%$detail%")));
                }
            }
        }
        foreach ($params as $param) {
            if (strlen(trim($param))) {
                $qb->orWhere($qb->expr()->like('doc.subject', $qb->expr()->literal("%$param%")));
                $qb->orWhere($qb->expr()->like('doc.body', $qb->expr()->literal("%$param%")));
            }
        }
        return $qb->getQuery()->execute();
    }

    public function findDistinctYears()
    {
        $qb = $this->createQueryBuilder('doc');
        $qb->select('DISTINCT doc.yearCreated');
        $qb->orderBy('doc.yearCreated');
        return $qb->getQuery()->execute();
    }

    public function countByYear($year)
    {
        $qb = $this->createQueryBuilder('doc');
        $qb->select('COUNT(doc)');
        $qb->where($qb->expr()->eq('doc.yearCreated', $year));
        return $qb->getQuery()->getSingleScalarResult();
    }
    public function countPropertyByYear($year, $property, $value = null)
    {
        $qb = $this->createQueryBuilder('doc');
        $qb->select('COUNT(doc)');
        $qb->innerJoin('doc.documentProperties', 'prop');
        $qb->where($qb->expr()->eq('doc.yearCreated', $year));
        $qb->andWhere($qb->expr()->eq('prop.name', ':property'));
        $qb->setParameter('property', $property);
        if ($value) {
            $qb->andWhere($qb->expr()->eq('prop.value', ':value'));
            $qb->setParameter('value', $value);
        }
        return $qb->getQuery()->getSingleScalarResult();
    }

    // /**
    //  * @return Document[] Returns an array of Document objects
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
    public function findOneBySomeField($value): ?Document
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
