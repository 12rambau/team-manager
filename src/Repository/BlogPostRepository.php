<?php

namespace App\Repository;

use App\Entity\BlogPost;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method BlogPost|null find($id, $lockMode = null, $lockVersion = null)
 * @method BlogPost|null findOneBy(array $criteria, array $orderBy = null)
 * @method BlogPost[]    findAll()
 * @method BlogPost[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BlogPostRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, BlogPost::class);
    }

    /**
     * @return posts[] Returns an array of X blogPosts
     */
    public function findSome(int $offset, int $number)
    {
        $posts = $this->createQueryBuilder('b')
            ->orderBy('b.publishDate', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
        if ($posts == null)
            $posts = [];

        return $posts;

    }

    /**
     * @return int the number of posts in the db
     */
    public function countAll()
    {
        $qb = $this->createQueryBuilder('b');

        $qb->select('COUNT(b)');

        $result = $qb->getQuery()->getSingleScalarResult();

        return ($result)?$result:0;
    }
}
