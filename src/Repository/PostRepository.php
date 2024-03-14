<?php

namespace App\Repository;

use App\Entity\Post;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Post>
 *
 * @method Post|null find($id, $lockMode = null, $lockVersion = null)
 * @method Post|null findOneBy(array $criteria, array $orderBy = null)
 * @method Post[]    findAll()
 * @method Post[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Post::class);
    }

    public const LIGNE_PAR_PAGE = 4;
    public function getPaginator(int $offset, bool $byJaime): Paginator
    {
        $query = $this->createQueryBuilder('p');
        if ($byJaime) {
            $query = $query->select('p, COUNT(j) AS HIDDEN likeCount')
                ->leftJoin('p.jaime', 'j')
                ->orderBy('likeCount', 'DESC')
                ->groupBy('p.id');
        } else {
            $query = $query->orderBy('p.datePost', 'DESC');
        }
        $query = $query->setMaxResults(self::LIGNE_PAR_PAGE)
            ->setFirstResult($offset)
            ->getQuery();
        return new Paginator($query);
    }

    //    public function findOneBySomeField($value): ?Post
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
