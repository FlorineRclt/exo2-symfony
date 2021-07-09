<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }


    public function searchByTerm($term)
    {
        //createQueryBuilder permet de créer des requêtes SQL à partir de php
        $queryBuilder = $this->createQueryBuilder('article');

        //on défini la requête pour aller chercher l'article contenant le terme recherché
        $query = $queryBuilder
            -> select('article')

            ->leftJoin('article.category', 'category')
            ->leftJoin('article.tag', 'tag')

            ->where('article.content LIKE :term')
            ->orWhere('article.title LIKE :term')
            ->orWhere('category.title LIKE :term')
            ->orWhere('tag.title LIKE :term')

            //Le setParameter est une sécurité, permet de filtrer ce qui est envoyé par l'utilisateur
            // afin d'éviter le contenu dangereux (requêtes SQL)
            -> setParameter('term', '%'.$term.'%')
            -> getQuery();

        //on renvoie le resultat de la recherche
        return $query -> getResult();

        //Ensuite on va dans l'ArticleController pour créer la méthode qui permettra de récupérer
        // et afficher les résultats obtenus
    }












    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
