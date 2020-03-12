<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;
use Exception;

/**
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    // /**
    //  * @return Invoice[] Returns an array of Invoice objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Invoice
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function findOneById($value): ?Invoice
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.id = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }
    public function findByClient($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.client = :val')
            ->setParameter('val', $value)
            ->andWhere('i.visible = :valu')
            ->setParameter('valu', true)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findByEnterprise($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.enterprise = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'DESC')
            ->getQuery()
            ->getResult();
    }
    public function findByLoQueSea($value)
    {
        return $this->createQueryBuilder('i')

            ->innerJoin('i.client', 'c',  'c.id = i.client')
            ->orwhere('c.name = :clientid')
            ->setParameter('clientid', $value)
            ->orwhere('i.description LIKE :val')
            ->setParameter('val', '%' . $value . '%')
            ->orwhere('i.date LIKE :valu')
            ->setParameter('valu', '%' . $value . '%')

            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    public function findOneByIdJoinedToClient($value, $identerprise)
    {
        $query = $this->getEntityManager('c')
            ->createQuery(
            'SELECT i , c FROM App:Invoice i 
            JOIN i.client c 
            WHERE i.enterprise = :valu
            AND i.description LIKE :val
            OR c.name LIKE :val
            OR c.address LIKE :val
            OR i.subtotal LIKE :val
            OR i.total LIKE :val
            OR i.date LIKE :val
            OR i.invoicenumber LIKE :val
            ORDER BY i.invoicenumber ASC '
            )
            ->setParameter('val', '%' . $value . '%')
            ->setParameter('valu', '%' . $identerprise . '%');

            // throw new Exception($query->getResult());

        return $query->getResult();
    }

    public function findOneByLastDay($value): ?array
    {
        $query = $this->getEntityManager()
            ->createQuery(
            'SELECT MAX(i.date) FROM App:Invoice i WHERE i.enterprise = :val '            
            )->setParameter('val', $value );
        return $query->getResult();
    }
    // public function findOneByIdJoinedToCategory($value, $identerprise)
    // {
    //     $entityManager = $this->getEntityManager();

    //     $query = $entityManager->createQuery(
    //         'SELECT i 
    //             FROM App:Invoice i , App:Client c
    //             WHERE i.enterprise = ' . $identerprise . '
    //             AND i.description LIKE :val
    //             OR c.name LIKE :val
    //             OR c.address LIKE :val
    //             OR i.subtotal LIKE :val
    //             OR i.total LIKE :val
    //             OR i.date LIKE :val
    //             OR i.invoicenumber LIKE :val
    //             ORDER BY i.invoicenumber ASC'
    //     )
    //         ->setParameter('val', '%' . $value . '%');

    //     return $query->getResult();
    // }


}
