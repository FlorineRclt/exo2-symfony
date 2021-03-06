<?php


namespace App\Controller\Front;

use App\Entity\Article;
use App\Entity\Tag;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FrontArticleController extends AbstractController
{

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

        return $this->render('Front/article_list.html.twig', [
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

        return $this->render('Front/article_show.html.twig', [
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
        return $this->render('Front/article_search.html.twig', [
            'articles' => $articles,
            'term' => $term
        ]);
    }
}