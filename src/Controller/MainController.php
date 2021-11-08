<?php

namespace App\Controller;

use App\Entity\Actus;
use App\Form\ContactType;
use App\Repository\ActusRepository;
use App\Repository\FormuleRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * page d'accueil du site
     * 
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig');
    }

    /**
     * Page de présentation
     * 
     * @Route("/presentation", name="presentation")
     * @return Response
     */
    public function presentation(): Response
    {
        return $this->render('main/presentation.html.twig');
    }


    /**
     * Page de prestation
     * 
     * @Route("/presta", name="presta")
     * @return Response
     */
    public function presta(): Response
    {
        return $this->render('main/prestation.html.twig');
    }

    /**
     * Page des actualités
     * 
     * @Route("/actus", name="actus")
     * @return Response
     */
    public function actus(ActusRepository $actusRepo): Response
    {
        $actus = $actusRepo->findBy(['active' => true]);

        return $this->render('main/actus.html.twig', [
            'actus' => $actus
        ]);
    }

    /**
     * Page de détail d'une actu
     * 
     * @Route("/actu/details/{id}", name="actu_detail")
     */
    public function detailActu(Actus $actu): Response
    {
        return $this->render('main/detail.html.twig', [
            'actu' => $actu
        ]);
    }

    
    /**
     * Page des formules
     * 
     * @Route("/formules", name="formules")
     * @return Response
     */
    public function tarifs(FormuleRepository $formuleRepo): Response
    {
        return $this->render('main/formules.html.twig', [
            'formules' => $formuleRepo->findBy(['active' => true])
        ]);
    }


    /**
     * @Route("/contact", name="contact")
     */
    public function contact(HttpFoundationRequest $request, MailerInterface $mailer): Response
    {
        $form = $this->createForm(ContactType::class);

        $contact = $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // if( $contact->get('coaching_one_to_one')->getData() == true){
            //     $coaching_one_to_one = 'coaching_one_to_one';
            // }
            $email = (new TemplatedEmail())
                    ->from($contact->get('email')->getData())
                    ->to(new Address('moi@mail.fr'))
                    ->subject('Contact : ' . $contact->get('sujet')->getData())
                    ->htmlTemplate('email/contact.html.twig')
                    ->context([
                        'mail' => $contact->get('email')->getData(),
                        'prenom' => $contact->get('prenom')->getData(),
                        'nom' => $contact->get('nom')->getData(),
                        'tel' => $contact->get('tel')->getData(),
                        'sujet' => $contact->get('sujet')->getData(),
                        'formule' => $contact->get('formule')->getData(),
                        'support' => $contact->get('support')->getData(),
                        'message' => $contact->get('message')->getData()
                    ]);
            $mailer->send($email);

            $this->addFlash('email', 'Votre email a bien été envoyé, vous receverez une réponse dans les plus brefs délais !');

            return $this->redirectToRoute('contact');
        }
        
        return $this->render('main/contact.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
