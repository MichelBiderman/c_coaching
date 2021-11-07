<?php

namespace App\Controller;

use App\Entity\Avis;
use App\Form\AvisType;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{
    /**
     * @Route("/avis", name="avis")
     */
    public function index(AvisRepository $avisRepo): Response
    {
        return $this->render('avis/index.html.twig', [
            'avisAll' => $avisRepo->findBy(['active' => true], ['createdAt' => 'desc'])
        ]);
    }

    /**
     * Ajout d'un commentaire
     * 
     * @Route("/avis/ajout", name="ajout_avis")
     */
    public function addAvis(Request $request)
    {
        $avis = new Avis;

        $form = $this->createForm(AvisType::class, $avis);
        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){

            $avis->setPseudo($form->get('pseudo')->getData());
            $avis->setComment($form->get('comment')->getData());
            $avis->setActive(false);

            $em = $this->getDoctrine()->getManager();
            $em->persist($avis);
            $em->flush();
        
            $this->addFlash('message', 'Votre commentaire a été enregistré, il sera publié dans les plus brefs délais !');

            return $this->redirectToRoute('avis');

        }

        return $this->render('avis/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
