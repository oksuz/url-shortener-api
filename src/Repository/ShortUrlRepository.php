<?php

namespace App\Repository;

use App\Entity\ShortUrl;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShortUrl|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShortUrl|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShortUrl[]    findAll()
 * @method ShortUrl[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShortUrlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShortUrl::class);
    }

    /**
     * @param ShortUrl $shortUrl
     * @return ShortUrl|null
     * @throws ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(ShortUrl $shortUrl): ?ShortUrl
    {
        $this->getEntityManager()->persist($shortUrl);
        $this->getEntityManager()->flush();
        return $shortUrl;
    }

    // /**
    //  * @return ShortUrl[] Returns an array of ShortUrl objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShortUrl
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
