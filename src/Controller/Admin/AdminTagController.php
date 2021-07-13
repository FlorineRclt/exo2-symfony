<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Tag;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminTagController extends AbstractController
{

    /**
     * @Route("/admin/tag/insert", name="adminTagInsert")
     */
    public function insertTag(EntityManagerInterface $entityManager)
    {
        // J'utilise l'entité Tag, pour créer un nouveau tag en bdd
        // une instance de l'entité Tag = un enregistrement de tag en bdd
        $tag = new Tag();

        // j'utilise les setters de l'entité Tag pour renseigner les valeurs
        // des colonnes
        $tag->setTitle('Nouveau tag');
        $tag->setColor('lightseagreen');


        // je prends toutes les entités créées (ici une seule) et je les "pré-sauvegarde"
        $entityManager->persist($tag);

        // je récupère toutes les entités pré-sauvegardées et je les insère en BDD
        $entityManager->flush();

        return $this->redirectToRoute("adminTagList");
    }


    /**
     * @Route("/admin/tags/update/{id}", name="adminTagUpdate")
     */
    public function tagUpdate($id, EntityManagerInterface $entityManager, TagRepository $tagRepository)
    {
        //on va chercher le tag que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $tag = $tagRepository->find($id);

        //on modifie le paramètre qui doit être mis à jour, dans ce cas : le titre
        $tag->setTitle('Tag mis à jour');
        $tag->setColor('lightpink');

        //on pré-sauvergarde puis on envoie l'entité dans la base de données
        $entityManager->persist($tag);
        $entityManager->flush();

        return $this->redirectToRoute("adminTagList");
    }


    /**
     * @Route("/admin/tags/delete/{id}", name="adminTagDelete")
     */
    public function deleteTag($id, TagRepository $tagRepository, EntityManagerInterface $entityManager)
    {
        //on va chercher le tag que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $tag = $tagRepository->find($id);

        //on supprime et on traduit l'ordre en requete SQL via le flush
        $entityManager->remove($tag);
        $entityManager->flush();

        //on redirige l'utilisateur vers la page tagList une fois que les opérations sont terminées
        return $this->redirectToRoute("adminTagList");
    }



    /**
     * @Route("/admin/tags", name="adminTagList")
     */
    public function tagList(TagRepository $tagRepository){

        $tags = $tagRepository->findAll();
        return $this->render('Admin/admin_tag_list.html.twig', [
            'tags' => $tags
        ]);
    }


    /**
     * @Route("/admin/tags/{id}", name="adminTagShow")
     */
    public function tagShow($id, TagRepository $tagRepository){
        $tag = $tagRepository->find($id);

        // si le tag n'a pas été trouvé, je renvoie une exception (erreur)
        // pour afficher une 404
        if (is_null($tag)) {
            throw new NotFoundHttpException();
        }

        return $this->render('Admin/admin_tag_show.html.twig', [
            'tag' => $tag
        ]);
    }

}