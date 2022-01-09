<?php

namespace App\Repository;

use App\Entity\Plattforms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Plattforms|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plattforms|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plattforms[]    findAll()
 * @method Plattforms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlattformsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plattforms::class);
    }

    public function findByName($value)
    {
		return $this->createQueryBuilder('p')
			->where('upper(p.name) = upper(:name)')
			->setParameter('name', $value)
			->getQuery()
			->getResult()
		;
	}

    public function findLikeName($value)
    {
		return $this->createQueryBuilder('p')
			->where('upper(p.name) LIKE upper(:name)')
			->setParameter('name', '%' . $value . '%')
			->getQuery()
			->getResult()
		;
	}

    // /**
    //  * @return Plattforms[] Returns an array of Plattforms objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Plattforms
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
