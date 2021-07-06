<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class CategoryController extends AbstractController
{



//"base de données"
    private $categories = [
        1 => [
            "title" => "Politique",
            "content" => "Tous les articles liés à Jean Lassalle",
            "id" => 1,
            "published" => true,
        ],
        2 => [
            "title" => "Economie",
            "content" => "Les meilleurs tuyaux pour avoir DU FRIC",
            "id" => 2,
            "published" => true
        ],
        3 => [
            "title" => "Securité",
            "content" => "Attention les étrangers sont très méchants",
            "id" => 3,
            "published" => false
        ],
        4 => [
            "title" => "Ecologie",
            "content" => "Hummer <3",
            "id" => 4,
            "published" => true
        ]
    ];


    /**
     * @Route("/categories", name="categoryList")
     */
    public function categoryList ()
    {
        return $this->render('category_list.html.twig', [
            'categories' => $this->categories
        ]);
    }


    /**
     * @Route("/category/{id}", name="categoryShow")
     */
    public function categoryShow ($id)
    {
        /*on va chercher la page html twig et on l'interprete dans le navigateur
         on lui envoie les données du tableau pour pouvoi rtravailler dessus */
        return $this->render('category_show.html.twig', [
            'category' => $this->categories[$id]
        ]);
    }
}