<?php


namespace App\Controller\Admin;


use App\Entity\Category;
use App\Entity\Tag;
use App\Form\CategoryType;
use App\Form\TagType;
use App\Repository\CategoryRepository;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminTagController extends AbstractController
{

    /**
     * @Route("/admin/tag/insert", name="adminTagInsert")
     */
    public function insertTag(EntityManagerInterface $entityManager, Request $request)
    {
        // J'utilise l'entité Tag, pour créer un nouveau tag en bdd
        // une instance de l'entité Tag = un enregistrement de tag en bdd
        $tag = new Tag();

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Tag
        $tagForm = $this->createForm(TagType::class, $tag);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $tagForm->handleRequest($request);

        // si le formulaire à été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre le tag crée dans la bdd
        if ($tagForm->isSubmitted() && $tagForm->isValid()) {

            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'Le tag '. $tag->getTitle().' a bien été crée !'
            );

            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute("adminTagList");
        }

        return $this->render('admin/admin_insert_tag.html.twig', [
            'tagForm' => $tagForm->createView()
        ]);
    }


    /**
     * @Route("/admin/tags/update/{id}", name="adminTagUpdate")
     */
    public function tagUpdate(
        $id,
        EntityManagerInterface $entityManager,
        TagRepository $tagRepository,
        Request $request)
    {
        //on va chercher le tag que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $tag = $tagRepository->find($id);

        // on génère le formulaire en utilisant le gabarit + une instance de l'entité Tag
        $tagForm = $this->createForm(TagType::class, $tag);

        // on lie le formulaire aux données de POST (aux données envoyées en POST)
        $tagForm->handleRequest($request);

        // si le formulaire à été posté et qu'il est valide (que tous les champs
        // obligatoires sont remplis correctement), alors on enregistre le tag crée dans la bdd
        if ($tagForm->isSubmitted() && $tagForm->isValid()) {

            //permet de stocker en session un message flash,
            // dans le but de l'afficher sur la page suivante
            $this->addFlash(
                'success',
                'Le tag '. $tag->getTitle().' a bien été modifié !'
            );

            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute("adminTagList");
        }

        return $this->render('admin/admin_insert_tag.html.twig', [
            'tagForm' => $tagForm->createView()
        ]);
    }


    /**
     * @Route("/admin/tags/delete/{id}", name="adminTagDelete")
     */
    public function deleteTag($id, TagRepository $tagRepository, EntityManagerInterface $entityManager)
    {
        //on va chercher le tag que l'on veut modifier à l'aide son id et de la méthode find
        //en utilisant la wildcard dans l'URL
        $tag = $tagRepository->find($id);

        //permet de stocker en session un message flash,
        // dans le but de l'afficher sur la page suivante
        $this->addFlash(
            'success',
            'Le tag '. $tag->getTitle().' a bien été supprimé !'
        );

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