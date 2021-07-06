<?php


namespace App\Controller;

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
        Return $this->render ('accueil.html.twig');
    }

}