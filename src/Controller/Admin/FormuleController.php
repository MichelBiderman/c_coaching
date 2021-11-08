<?php

namespace App\Controller\Admin;

use App\Entity\Formule;
use App\Form\FormuleType;
use App\Repository\FormuleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FormuleController extends AbstractController
{
/** 
     * Page d'index des formules
     * 
     * @Route("/admin/formules", name="admin_formules")
    */
    public function formules(FormuleRepository $formuleRepo): Response
    {
        return $this->render('admin/formules/index.html.twig', [
            'formules' => $formuleRepo->findAll()
        ]);
    }

    /**
     * Page de visualisation de toutes les formules
     * 
     * @Route("/admin/formules/preview", name="admin_formules_preview")
     */
    public function showFormules(FormuleRepository $formuleRepo): Response
    {
        return $this->render('admin/formules/formulePreview.html.twig', [
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
            $formule->setDescription($form->get('description')->getData());
            $formule->setPrix($form->get('prix')->getData());
            $formule->setActive(false);

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
            // Récupération de l'image si changement d'image
            $image = $form->get('image')->getData();
            if($image){
                $fichier = md5(uniqid()) . '.' . $image->guessExtension();

                // On copie le fichier dans le dossier upload
                $image->move(
                    $this->getParameter('images_directory'),
                    $fichier
                );
                $formule->setImage($fichier);
            }
            
            $formule->setName($form->get('name')->getData());
            $formule->setDescription($form->get('description')->getData());
            $formule->setPrix($form->get('prix')->getData());

            $em = $this->getDoctrine()->getManager();
            $em->persist($formule);
            $em->flush();
        
            $this->addFlash('message', 'La formule ' . $formule->getName() . ' a bien été modifiée !');

            return $this->redirectToRoute('admin_formules');

        }

        return $this->render('admin/formules/edit.html.twig', [
            'form' => $form->createView(),
            'image' => $formule->getImage(),
            'titre' => 'Modifier la formule ci-dessous:'
        ]);
    }

    /**
     * Activation de la formule
     * 
     * @Route("admin/admin/formule/activer/{id}", name="activer_formule")
     */
    public function activerFormule(Formule $formule)
    {
        $formule->setActive($formule->getActive() ? false : true);

        $em = $this->getDoctrine()->getManager();
        $em->persist($formule);
        $em->flush();

        return new Response("true");
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

        return $this->redirectToRoute('admin_formules');
    }
}