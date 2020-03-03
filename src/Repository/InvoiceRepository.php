<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
            ->orderBy('i.id', 'ASC')
            ->getQuery()
            ->getResult();
    }


    public function findByLoQueSea($value)
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c', 'client', 'p', 'c.user_id = p.id')
            ->orwhere('c.name = :clientid')
            ->orwhere('p.description LIKE :val')
            ->setParameter('clientid', $value)
            ->setParameter('val', '%' . $value . '%')
            ->orwhere('p.date LIKE :valu')
            ->setParameter('valu', '%' . $value . '%')

            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();






        // ->innerJoin('c.phones', 'p', 'WITH', 'p.phone = :phone')
        // ->orwhere('c.name = :clientid')
        // ->orwhere('p.description LIKE :val')
        // ->setParameter('clientid', $value)
        // ->setParameter('val', '%' . $value . '%')
        // ->orwhere('p.date LIKE :valu')
        // ->setParameter('valu', '%' . $value . '%')

        // ->orderBy('p.id', 'ASC')
        // ->setMaxResults(10)
        // ->getQuery()
        // ->getResult();
    }

    public function findOneByIdJoinedToCategory($value)
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            "SELECT * FROM INVOICE I INNER JOIN CLIENT C ON I.CLIENT_ID=C.ID 
WHERE DESCRIPTION LIKE p.id = :id
OR NAME LIKEp.id = :id
OR ADDRESS LIKE p.id = :id
OR SUBTOTAL LIKE p.id = :id
OR TOTAL LIKE p.id = :id
OR DATE LIKE p.id = :id 
OR INVOICENUMBER LIKE p.id = :id"
        )->setParameter('id', '%' . $value . '%');





        //     'SELECT p, c
        // FROM App\Entity\Product p
        // INNER JOIN p.category c
        // WHERE p.id = :id'
        // )->setParameter('id', $productId);

        return $query->getOneOrNullResult();
    }
}
