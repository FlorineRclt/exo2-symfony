<?php


namespace App\Controller\Admin;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use  Symfony\Component\Routing\Annotation\Route;

class AdminPageController extends AbstractController
{

    /**
     * @Route ("/admin/", name="adminHome")
     */
    public function home ()
    {
      /*on va chercher la page à afficher pour la route accueil
      et on l'interprete dans le navigateur*/
        Return $this->render ('Admin/admin_accueil.html.twig');
    }

}