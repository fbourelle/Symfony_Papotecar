<?php

namespace App\Repository;

use App\Entity\Travel;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Travel|null find($id, $lockMode = null, $lockVersion = null)
 * @method Travel|null findOneBy(array $criteria, array $orderBy = null)
 * @method Travel[]    findAll()
 * @method Travel[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Travel::class);
    }

    public function findPaginated($page = 1,$startCity = null, $endCity = null, $startHour = null)
    {
        $qb = $this->createQueryBuilder('t');
        $qb->addOrderBy('t.starthour', 'DESC');
        $qb->join('t.user', 'u')->addSelect('u');
        $qb->andWhere("t.startcity = :startCity");
        $qb->setParameter('startCity', $startCity);
        $qb->andWhere("t.endcity = :endCity");
        $qb->setParameter('endCity', $endCity);
        $qb->andWhere("t.starthour LIKE :startHour");
        $qb->setParameter('startHour', '%'.$startHour.'%');
        $qb->setMaxResults(50);
        $qb->setFirstResult(($page-1)*50);
        $query = $qb->getQuery();
        //$results = $query->getResult();
        return new Paginator($query);
    }

//    /**
//     * @return Travel[] Returns an array of Travel objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Travel
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
