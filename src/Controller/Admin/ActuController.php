<?php 

namespace App\Controller\Admin;

use App\Entity\Actus;
use App\Form\ActusType;
use App\Repository\ActusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActuController extends AbstractController
{
     /**
     * Page de gestion des actus
     *
     * @Route("/admin/actus", name="admin_actus")
     */
    public function gestionActus(ActusRepository $actusRepo): Response
    {
        $actus = $actusRepo->findAll();

        return $this->render('admin/actus/index.html.twig', [
            'actus' => $actus
        ]);
    }

    /**
     * Page de visualisation des actus
     *
     * @Route("/admin/actus/preview", name="admin_actus_preview")
     */
    public function showActus(ActusRepository $actusRepo): Response
    {
        $actus = $actusRepo->findAll();

        return $this->render('admin/actus/actusPreview.html.twig', [
            'actus' => $actus
        ]);
    }

    /**
     * Page de détail d'une actu
     * 
     * @Route("/admin/actu/details/{id}", name="admin_actu_detail")
     */
    public function detailActu(Actus $actu): Response
    {
        return $this->render('admin/actus/detail.html.twig', [
            'actu' => $actu
        ]);
    }


    /**
     * Page d'ajout d'une actu
     * 
     * @Route("/admin/actus/ajout", name="add_actus")
     */
    public function addActus(Request $request)
    {
        $actu = new Actus;

        $form = $this->createForm(ActusType::class, $actu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // Récupération de l'image si changement d'image
            $image = $form->get('image')->getData();
            $fichier = md5(uniqid()) . '.' . $image->guessExtension();

            // On copie le fichier dans le dossier upload
            $image->move(
                $this->getParameter('images_directory'),
                $fichier
            );

            $actu->setImage($fichier);
            $actu->setTitle($form->get('title')->getData());
            $actu->setDescription($form->get('description')->getData());      
            $actu->setActive(false);     

            $em = $this->getDoctrine()->getManager();
            $em->persist($actu);
            $em->flush();
        
            $this->addFlash('message', 'L\'actualité ' . $actu->getTitle() . ' a bien été ajoutée !');
            return $this->redirectToRoute('admin_actus');

        }

        return $this->render('admin/actus/editActus.html.twig', [
            'form' => $form->createView(),
            'image' => '',
            'titre' => "Ajout d'une actualité:"
        ]);
    }

    /**
     * Page de modification d'une actu
     * 
     * @Route("/admin/actu/modifier/{id}", name="modifier_actus")
     */
    public function modifierActu(Request $request, $id, Actus $actu)
    {
        $form = $this->createForm(ActusType::class, $actu);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $image = $form->get('image')->getData();
            if($image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $actu->setImage($fichier);
            }
            
            $actu->setTitle($form->get('title')->getData());
            $actu->setDescription($form->get('description')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($actu);
            $em->flush();

        
            $this->addFlash('message', 'L\'actualité ' . $actu->getTitle() . ' a bien été modifiée !');

            return $this->redirectToRoute('admin_actus');
        }

        return $this->render('admin/actus/editActus.html.twig', [
            'form' => $form->createView(),
            'image' => $actu->getImage(),
            'titre' => "Modofier l'actualité ci-dessous:"
        ]);
    }

    /**
     * Supression d'une actu
     * 
     * @Route("/admin/actu/delete/{id}", name="delete_actu")
     */
    public function deleteActu(Request $request, Actus $actu)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($actu);
        $em->flush();

        $this->addFlash('message', 'Actu supprimée avec succès !');

        return $this->redirectToRoute('admin_actus');
    }

    /**
     * Activation de l'actu
     * 
     * @Route("admin/admin/actu/activer/{id}", name="activer")
     */
    public function activer(Actus $actu)
    {
        $actu->setActive($actu->getActive() ? false : true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($actu);
        $em->flush();

        return new Response("true");
    }
}