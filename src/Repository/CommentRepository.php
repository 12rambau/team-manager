<?php

namespace App\Repository;

use App\Entity\Comment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    /**
     * @return comments[] Returns an array of X messages
     */
    public function findSome(int $offset, int $number)
    {
        $comments = $this->createQueryBuilder('b')
            ->orderBy('b.publishDate', 'DESC')
            ->setFirstResult($offset)
            ->setMaxResults($number)
            ->getQuery()
            ->getResult();
        if ($comments == null)
            $comments = [];

        return $comments;

    }
}
