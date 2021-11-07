<?php

namespace App\Controller\Admin;

use App\Entity\Avis;
use App\Repository\AvisRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AvisController extends AbstractController
{

    /**
     * Page admin des affichages des avis avant de les poster
     * 
     * @Route("/admin/avis", name="admin_avis")
     */
    public function index(AvisRepository $avisRepo): Response
    {
        return $this->render('admin/avis/index.html.twig', [
            'avisAll' => $avisRepo->findBy([], ['createdAt' => 'desc']),
            'avisPublic' => 'de tous les avis:',
            'liste' => 'all'
        ]);
    }
    /**
     * Page admin des des avis non publics
     * 
     * @Route("/admin/avis/hidden", name="admin_avis_hidden")
     */
    public function avisHidden(AvisRepository $avisRepo): Response
    {
        return $this->render('admin/avis/index.html.twig', [
            'avisAll' => $avisRepo->findBy(['active' => false], ['createdAt' => 'desc']),
            'avisPublic' => 'des avis non publiés:',
            'liste' => 'hidden'
        ]);
    }
    /**
     * Page admin des des avis publics
     * 
     * @Route("/admin/avis/ok", name="admin_avis_ok")
     */
    public function avis(AvisRepository $avisRepo): Response
    {
        return $this->render('admin/avis/index.html.twig', [
            'avisAll' => $avisRepo->findBy(['active' => true], ['createdAt' => 'desc']),
            'avisPublic' => 'des avis publiés:',
            'liste' => 'published'
        ]);
    }

    /**
     * Poster/Activer un avis
     * 
     * @Route("/admin/avis/activer/{id}", name="avis_activer")
     */
    public function activeAvis(Avis $avis)
    {
        // $avis->setActive($avis->getActive() ? false : true);

        if($avis->getActive() == false){
            $avis->setActive(true);
            $publie = "publié";
        }else{
            $avis->setActive(false);
            $publie = "enlevé des publications";
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($avis);
        $em->flush();

        $this->addFlash('message', 'le commentaire de "' . $avis->getPseudo() .'" bien été '. $publie. ' !' );

        return $this->redirectToRoute('admin_avis');
    }

    /**
     * Supression d'un commentaire/avis
     * 
     * @Route("/admin/avis/delete/{id}", name="avis_delete")
     */
    public function deleteAvis(Avis $avis)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($avis);
        $em->flush();

        $this->addFlash('message', 'Avis de "' .$avis->getPseudo().'" a été supprimé avec succès !');

        return $this->redirectToRoute('admin_avis');
    }
}