<?php


namespace App\Controller\Front;


use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FrontTagController extends AbstractController
{

    /**
     * @Route("/tags", name="tagList")
     */
    public function tagList(TagRepository $tagRepository){

        $tags = $tagRepository->findAll();
        return $this->render('Front/tag_list.html.twig', [
            'tags' => $tags
        ]);
    }


    /**
     * @Route("/tags/{id}", name="tagShow")
     */
    public function tagShow($id, TagRepository $tagRepository){
        $tag = $tagRepository->find($id);

        // si le tag n'a pas été trouvé, je renvoie une exception (erreur)
        // pour afficher une 404
        if (is_null($tag)) {
            throw new NotFoundHttpException();
        }

        return $this->render('Front/tag_show.html.twig', [
            'tag' => $tag
        ]);
    }

}