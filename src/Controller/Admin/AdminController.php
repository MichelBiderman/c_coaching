<?php

namespace App\Controller\Admin;

use App\Entity\Support;
use App\Form\SupportType;
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
