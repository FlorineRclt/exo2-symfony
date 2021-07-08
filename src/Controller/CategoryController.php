<?php


namespace App\Controller;


use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{

    /**
     * @Route("/categories", name="categoryList")
     */
    public function categoryList (CategoryRepository $categoryRepository)
    {
        $categories = $categoryRepository->findAll();

        return $this->render('category_list.html.twig', [
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/categories/{id}", name="categoryShow")
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
        return $this->render('category_show.html.twig', [
            'category' => $category
        ]);
    }
}