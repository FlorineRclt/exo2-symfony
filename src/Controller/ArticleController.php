<?php


namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{

    /**
     * @Route("/article/insert", name="articleInsert")
     */
    public function insertArticle(EntityManagerInterface $entityManager)
    {
        // J'utilise l'entité Article, pour créer un nouvel article en bdd
        // une instance de l'entité Article = un enregistrement d'article en bdd
        $article = new Article();

        // j'utilise les setters de l'entité Article pour renseigner les valeurs
        // des colonnes
        $article->setTitle('Titre article depuis le controleur');
        $article->setContent('blablabla');
        $article->setIsPublished(true);
        $article->setCreatedAt(new \DateTime('NOW'));

        // je prends toutes les entités créées (ici une seule) et je les "pré-sauvegarde"
        $entityManager->persist($article);

        // je récupère toutes les entités pré-sauvegardées et je les insère en BDD
        $entityManager->flush();

        dump('ok'); die;


    }


    /**
     * @Route("/articles", name="articleList")
     */
    public function articleList(ArticleRepository $articleRepository)
    {
        // je dois faire une requête SQL SELECT en bdd
        // sur la table article
        // La classe qui me permet de faire des requêtes SELECT est ArticleRepository
        // donc je dois instancier cette classe
        // pour ça, j'utilise l'autowire (je place la classe en argument du controleur,
        // suivi de la variable dans laquelle je veux que sf m'instancie la classe
        $articles = $articleRepository->findAll();

        return $this->render('article_list.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/articles/{id}", name="articleShow")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        if (is_null($article)) {
            throw new NotFoundHttpException();
        }

        return $this->render('article_show.html.twig', [
            'article' => $article
        ]);
    }


    /**
     * @Route("/search", name="search")
     */
    public function search(ArticleRepository $articleRepository, Request $request)
    {
        //le terme à rechercher
        $term = $request->query->get('q');

        //on va chercher notre méthode searchByTerm qu'on a crée au préalable dans l'ArticleRepository
        $articles = $articleRepository->searchByTerm($term);

        //On fait afficher la page de résultat, on lui envoie les données articles et term pour
        // pouvoir s'en servir dans le twig
        return $this->render('article_search.html.twig', [
            'articles' => $articles,
            'term' => $term
        ]);
    }
}