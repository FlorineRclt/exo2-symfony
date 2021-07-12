<?php


namespace App\Controller;

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

class ArticleController extends AbstractController
{

    /**
     * @Route("/article/insert", name="articleInsert")
     */
    public function insertArticle(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository
    )
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

        // je récupère la catégorie dont l'id est 1 en bdd
        // doctrine me créé une instance de l'entité category avec les infos de la catégorie de la bdd
        $category = $categoryRepository->find(1);

        // j'associé l'instance de l'entité categorie récupérée, à l'instance de l'entité article que je suis
        // en train de créer
        $article->setCategory($category);

        $tag = $tagRepository->findOneBy(['title' => 'info']);

        if (is_null($tag)) {
            $tag = new Tag();
            $tag->setTitle('info');
            $tag->setColor('blue');
        }

        $entityManager->persist($tag);

        $article->setTag($tag);


        // je prends toutes les entités créées (ici une seule) et je les "pré-sauvegarde"
        $entityManager->persist($article);

        // je récupère toutes les entités pré-sauvegardées et je les insère en BDD
        $entityManager->flush();

        dump('ok'); die;
    }


    /**
     * @Route("/articles/update/{id}", name="articleUpdate")
     */
    public function articleUpdate($id, EntityManagerInterface $entityManager, ArticleRepository $articleRepository)
    {
        //on va chercher l'article que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $article = $articleRepository->find($id);

        //on modifie le paramètre qui doit être mis à jour, dans ce cas : le titre
        $article->setTitle('Troisième article');

        //on pré-sauvergarde puis on envoie l'entité dans la base de données
        $entityManager->persist($article);
        $entityManager->flush();

        dump('ok update'); die;
    }


    /**
     * @Route("/articles/delete/{id}", name="articleDelete")
     */
    public function deleteArticle($id, ArticleRepository $articleRepository, EntityManagerInterface $entityManager)
    {
        //on va chercher l'article que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $article = $articleRepository->find($id);

        //on supprime et on traduit l'ordre en requete SQL via le flush
        $entityManager->remove($article);
        $entityManager->flush();

        //on redirige l'utilisateur vers la page articleList une fois que les opérations sont terminées
        return $this->redirectToRoute("articleList");


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