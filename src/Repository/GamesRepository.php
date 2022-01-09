<?php

namespace App\Repository;

use App\Entity\Games;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Games|null find($id, $lockMode = null, $lockVersion = null)
 * @method Games|null findOneBy(array $criteria, array $orderBy = null)
 * @method Games[]    findAll()
 * @method Games[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Games::class);
    }

	public function findAll()
    {
        return $this->findBy(array(), array('title' => 'ASC'));
    }

    public function findByTitle($value)
    {
		return $this->createQueryBuilder('g')
			->where('upper(g.title) = upper(:title)')
			->setParameter('title', $value)
			->getQuery()
			->getResult()
		;
	}

    public function findLikeTitle($value)
    {
		return $this->createQueryBuilder('g')
			->where('upper(g.title) LIKE upper(:title)')
			->setParameter('title', '%' . $value . '%')
			->getQuery()
			->getResult()
		;
	}

    public function findByDevelopers($value)
    {
		return $this->createQueryBuilder('g')
			->where('g.developers LIKE :dev')
			->setParameter('dev', '%' . $value . '%')
			->getQuery()
			->getResult()
		;
	}

    // /**
    //  * @return Games[] Returns an array of Games objects
    //  */
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
    public function findOneBySomeField($value): ?Games
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
