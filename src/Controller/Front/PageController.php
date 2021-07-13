<?php


namespace App\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use  Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{

    /**
     * @Route ("/", name="home")
     */
    public function home ()
    {
      /*on va chercher la page à afficher pour la route accueil
      et on l'interprete dans le navigateur*/
        Return $this->render ('Front/accueil.html.twig');
    }

}