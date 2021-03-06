<?php

namespace App\Repository;

use App\Entity\Developers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Developers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Developers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Developers[]    findAll()
 * @method Developers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DevelopersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Developers::class);
    }

    public function findByName($value)
    {
		return $this->createQueryBuilder('d')
			->where('upper(d.name) = upper(:name)')
			->setParameter('name', $value)
			->getQuery()
			->getResult()
		;
	}

    public function findLikeName($value)
    {
		return $this->createQueryBuilder('d')
			->where('upper(d.name) LIKE upper(:name)')
			->setParameter('name', '%' . $value . '%')
			->getQuery()
			->getResult()
		;
	}

    // /**
    //  * @return Developers[] Returns an array of Developers objects
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
    public function findOneBySomeField($value): ?Developers
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
