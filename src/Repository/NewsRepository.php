<?php

namespace App\Repository;

use App\Entity\News;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method News|null find($id, $lockMode = null, $lockVersion = null)
 * @method News|null findOneBy(array $criteria, array $orderBy = null)
 * @method News[]    findAll()
 * @method News[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NewsRepository extends ServiceEntityRepository
{
    private $entityManager;

    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, News::class);
        $this->entityManager = $entityManager;
    }

    public function create(News $news) :NewsRepository
    {
        $this->entityManager->persist($news);
        $this->entityManager->flush();
        return $this;
    }

    public function truncate() {
        $cmd = $this->entityManager->getClassMetadata(News::class);
        $connection = $this->entityManager->getConnection();
        $dbPlatform = $connection->getDatabasePlatform();
        $connection->query('SET FOREIGN_KEY_CHECKS=0');
        $q = $dbPlatform->getTruncateTableSql($cmd->getTableName());
        $connection->executeUpdate($q);
        $connection->query('SET FOREIGN_KEY_CHECKS=1');
    }

     /**
      * @return News[] Returns an array of News objects
      */

    public function findFirst15(): array
    {
        return $this->createQueryBuilder('n')
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(15)
            ->getQuery()
            ->getResult()
        ;
    }



    public function findOneBySomeField($value): ?News
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
