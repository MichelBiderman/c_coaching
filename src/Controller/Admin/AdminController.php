<?php

namespace App\Controller\Admin;

use App\Entity\Actus;
use App\Entity\Formule;
use App\Entity\Support;
use App\Form\ActusType;
use App\Form\FormuleType;
use App\Form\SupportType;
use App\Repository\ActusRepository;
use App\Repository\FormuleRepository;
use App\Repository\SupportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }


    /** 
     * Page d'index des formules
     * 
     * @Route("/admin/formules", name="formules")
    */
    public function formules(FormuleRepository $formuleRepo): Response
    {
        return $this->render('admin/formules/index.html.twig', [
            'formules' => $formuleRepo->findAll()
        ]);
    }

    /**
     * @Route("/admin/formule/ajout", name="add_formule")
     */
    public function addFormule(Request $request)
    {
        $formule = new Formule;

        $form = $this->createForm(FormuleType::class, $formule);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $formule->setName($form->get('name')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($formule);
            $em->flush();
        
            $this->addFlash('message', 'La formule ' . $formule->getName() . ' a bien été ajoutée !');

        }

        return $this->render('admin/formules/edit.html.twig', [
            'form' => $form->createView(),
            'titre' => "Ajout d'une formule:"
        ]);
    }

    /**
     * Page de modification des formules
     * 
     * @Route("admin/formule/edit/{id}", name="edit_formule")
     */
    public function editFormule(Request $request, Formule $formule)
    {

        $form = $this->createForm(FormuleType::class, $formule);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $formule->setName($form->get('name')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($formule);
            $em->flush();
        
            $this->addFlash('message', 'La formule ' . $formule->getName() . ' a bien été modifiée !');

            return $this->redirectToRoute('formules');

        }

        return $this->render('admin/formules/edit.html.twig', [
            'form' => $form->createView(),
            'titre' => 'Modifier la formule ci-dessous:'
        ]);
    }

    /**
     * Supression d'une formule
     * 
     * @Route("/admin/formule/delete/{id}", name="delete-formule")
     */
    public function deleteFormule(Request $request, Formule $formule)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($formule);
        $em->flush();

        $this->addFlash('message', 'Formule supprimée avec succès !');

        return $this->redirectToRoute('formules');
    }

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

    /**
     * Page d'index des supports
     * 
     * @Route("/admin/supports", name="admin_supports")
     */
    public function supports(SupportRepository $supportRepo)
    {
        return $this->render('admin/supports/index.html.twig', [
            'supports' => $supportRepo->findAll()
        ]);
    }

    /**
     * Ajout d'un support
     * 
     * @Route("/admin/support/ajout", name="add_support")
     */
    public function addSupport(Request $request)
    {
        $support = new Support;

        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $support->setName($form->get('name')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($support);
            $em->flush();
        
            $this->addFlash('message', 'Le support: "' . $support->getName() . '" a bien été ajouté avec succès !');

            return $this->redirectToRoute('admin_supports');

        }

        return $this->render('admin/supports/edit.html.twig', [
            'form' => $form->createView(),
            'titre' => "Ajout d'un support:"
        ]);
    }

    /**
     * Modification d'un support
     * 
     * @Route("/admin/support/modifier/{id}", name="edit_support")
     */
    public function editSupport(Request $request, Support $support)
    {
        $form = $this->createForm(SupportType::class, $support);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){

            $support->setName($form->get('name')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($support);
            $em->flush();
        
            $this->addFlash('message', 'Le support: "' . $support->getName() . '" a été modifié avec succès !');

            return $this->redirectToRoute('admin_supports');

        }

        return $this->render('admin/supports/edit.html.twig', [
            'form' => $form->createView(),
            'titre' => "Modifier le support ci-dessous:"
        ]);
    }

    /**
     * Supression d'un support
     * 
     * @Route("/admin/support/delete/{id}", name="delete_support")
     */
    public function deleteSupport(Support $support)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($support);
        $em->flush();

        $this->addFlash('message', 'Le support: "' . $support->getName().'" a été supprimé avec succès !');

        return $this->redirectToRoute('admin_supports');
    }
}
