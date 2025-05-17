<?php



namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }

    /**
     * Get latest articles ordered by published date descending
     */
    public function findLatestArticles(int $limit = 10)
    {
        return $this->createQueryBuilder('a')
            ->orderBy('a.dateArt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Get featured articles (assuming you have a boolean 'featured' field)
     */
    public function findFeaturedArticles(int $limit = 10)
    {
        return $this->createQueryBuilder('a')
            ->where('a.dateArt = :dateArt')
            ->setParameter('dateArt', true)
            ->orderBy('a.dateArt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
