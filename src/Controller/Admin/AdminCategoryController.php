<?php


namespace App\Controller\Admin;



use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class AdminCategoryController extends AbstractController
{

    /**
     * @Route("/admin/category/insert", name="adminCategoryInsert")
     */
    public function insertCategory(
        EntityManagerInterface $entityManager,
        CategoryRepository $categoryRepository)
    {
        // J'utilise l'entité Category, pour créer une nouvelle categorie en bdd
        // une instance de l'entité Category = un enregistrement de categorie en bdd
        $category = new Category();

        // j'utilise les setters de l'entité Category pour renseigner les valeurs
        // des colonnes
        $category->setTitle('Nouvelle catégorie');
        $category->setDescription('Tous les articles de la nouvelle catégorie');


        // je prends toutes les entités créées (ici une seule) et je les "pré-sauvegarde"
        $entityManager->persist($category);

        // je récupère toutes les entités pré-sauvegardées et je les insère en BDD
        $entityManager->flush();

        return $this->redirectToRoute("adminCategoryList");
    }


    /**
     * @Route("/admin/categories/update/{id}", name="adminCategoryUpdate")
     */
    public function categoryUpdate($id, EntityManagerInterface $entityManager, CategoryRepository $categoryRepository)
    {
        //on va chercher la catégorie que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $category = $categoryRepository->find($id);

        //on modifie le paramètre qui doit être mis à jour, dans ce cas : le titre
        $category->setTitle('Categorie mise à jour');

        //on pré-sauvergarde puis on envoie l'entité dans la base de données
        $entityManager->persist($category);
        $entityManager->flush();

        return $this->redirectToRoute("adminCategoryList");
    }


    /**
     * @Route("/admin/categories/delete/{id}", name="adminCategoryDelete")
     */
    public function deleteCategory($id, CategoryRepository $categoryRepository, EntityManagerInterface $entityManager)
    {
        //on va chercher la catégorie que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $category = $categoryRepository->find($id);

        //on supprime et on traduit l'ordre en requete SQL via le flush
        $entityManager->remove($category);
        $entityManager->flush();

        //on redirige l'utilisateur vers la page catégorieList une fois que les opérations sont terminées
        return $this->redirectToRoute("adminCategoryList");
    }


    /**
     * @Route("/admin/categories", name="adminCategoryList")
     */
    public function categoryList (CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('Admin/admin_category_list.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/admin/categories/{id}", name="adminCategoryShow")
     */
    public function categoryShow ($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        // si le tag n'a pas été trouvé, je renvoie une exception (erreur)
        // pour afficher une 404
        if (is_null($category)) {
            throw new NotFoundHttpException();
        }

        /*on va chercher la page html twig et on l'interprete dans le navigateur
         on lui envoie les données du tableau pour pouvoir travailler dessus */
        return $this->render('Admin/admin_category_show.html.twig', [
            'category' => $category
        ]);
    }
}