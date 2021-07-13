<?php


namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminArticleController extends AbstractController
{

//--CREATION D'UN FORMULAIRE POUR INSERER UN NOUVEL ARTICLE (de façon dynamique)--
    /**
     * @Route("/admin/article/insert", name="adminArticleInsert")
     */
    public function insertArticle(Request $request, EntityManagerInterface $entityManager)
    {
        $article = new Article();

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Article
        $articleForm = $this->createForm(ArticleType::class, $article);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $articleForm->handleRequest($request);

        // si le formulaire à été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre l'article crée dans la bdd
        if ($articleForm->isSubmitted() && $articleForm->isValid()) {
            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('adminArticleList');
        }

        return $this->render('admin/admin_insert.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);

    }


    /**
     * @Route("/admin/articles/update/{id}", name="adminArticleUpdate")
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

        return $this->redirectToRoute("adminArticleList");
    }


    /**
     * @Route("/admin/articles/delete/{id}", name="adminArticleDelete")
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
        return $this->redirectToRoute("adminArticleList");


    }

    /**
     * @Route("/admin/articles", name="adminArticleList")
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

        return $this->render('Admin/admin_article_list.html.twig', [
            'articles' => $articles
        ]);
    }


    /**
     * @Route("/admin/articles/{id}", name="adminArticleShow")
     */
    public function articleShow($id, ArticleRepository $articleRepository)
    {
        $article = $articleRepository->find($id);

        if (is_null($article)) {
            throw new NotFoundHttpException();
        }

        return $this->render('Admin/admin_article_show.html.twig', [
            'article' => $article
        ]);
    }


    /**
     * @Route("/admin/search", name="adminSearch")
     */
    public function search(ArticleRepository $articleRepository, Request $request)
    {
        //le terme à rechercher
        $term = $request->query->get('q');

        //on va chercher notre méthode searchByTerm qu'on a crée au préalable dans l'ArticleRepository
        $articles = $articleRepository->searchByTerm($term);

        //On fait afficher la page de résultat, on lui envoie les données articles et term pour
        // pouvoir s'en servir dans le twig
        return $this->render('Admin/admin_article_search.html.twig', [
            'articles' => $articles,
            'term' => $term
        ]);
    }



    ///---METHODE INSERT STATIQUE (pour comprendre le fonctionnement mais pas utile dans un site dynamique)---///

//    /**
//     * @Route("/admin/article/insertStatic", name="adminArticleInsertStatic")
//     */
//    public function insertArticle(
//        EntityManagerInterface $entityManager,
//        CategoryRepository $categoryRepository,
//        TagRepository $tagRepository
//    )
//    {
//        // J'utilise l'entité Article, pour créer un nouvel article en bdd
//        // une instance de l'entité Article = un enregistrement d'article en bdd
//        $article = new Article();
//
//        // j'utilise les setters de l'entité Article pour renseigner les valeurs
//        // des colonnes
//        $article->setTitle('Titre article depuis le controleur');
//        $article->setContent('blablabla');
//        $article->setIsPublished(true);
//        $article->setCreatedAt(new \DateTime('NOW'));
//
//        // je récupère la catégorie dont l'id est 1 en bdd
//        // doctrine me créé une instance de l'entité category avec les infos de la catégorie de la bdd
//        $category = $categoryRepository->find(1);
//
//        // j'associé l'instance de l'entité categorie récupérée, à l'instance de l'entité article que je suis
//        // en train de créer
//        $article->setCategory($category);
//
//        $tag = $tagRepository->findOneBy(['title' => 'info']);
//
//        if (is_null($tag)) {
//            $tag = new Tag();
//            $tag->setTitle('info');
//            $tag->setColor('blue');
//        }
//
//        $entityManager->persist($tag);
//
//        $article->setTag($tag);
//
//
//        // je prends toutes les entités créées (ici une seule) et je les "pré-sauvegarde"
//        $entityManager->persist($article);
//
//        // je récupère toutes les entités pré-sauvegardées et je les insère en BDD
//        $entityManager->flush();
//
//        return $this->redirectToRoute("adminArticleList");
//    }



}