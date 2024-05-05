<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\DevisRepository;
use App\Service\UserStatusService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{
    private $statusService;

    public function __construct(UserStatusService $statusService) {
        $this->statusService = $statusService;
    }

    #[Route('/devis', name: 'app_devis')]
    public function Devis(DevisRepository $DevisRepository)
    {
        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }
        
        $Devis = $DevisRepository->findAll();
        return $this->render('devis/listesDevis.html.twig', [
            'devis' => $Devis,
        ]);
    }

    /*Fonction d'ajout d'un Devis*/
    #[Route('/ajouterdevis', name: 'app_ajouter_devis')]
    public function ajouterDevis(Request $request, EntityManagerInterface $em)
    {   
        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }

        $Devis = new Devis(); 
        $form = $this->createForm(DevisType::class, $Devis);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ 
            $em ->persist($Devis);
            $em ->flush();
            return $this->redirectToRoute('app_devis');
        }
    

        return $this->render('devis/ajouterDevis.html.twig',[
            array(
                'form'=>$form->createView()
            )
        ]);
    }

    /*Fonction de modification d'un Devis*/
    #[Route("/modifierdevis/{id<\d+>}", name: "app_modifier_devis")]
    public function modifierDevis(Request $request, Devis $Devis, EntityManagerInterface $em)
    {
        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }

        $form = $this->createForm(DevisType::class, $Devis);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_devis');
        }
        return $this->render('devis/modifierDevis.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /*Fonction de suppression d'un Devis*/
    #[Route("/supprimerdevis/{id<\d+>}", name: "app_supprimer_devis")]
    public function supprimerDevis(Devis $Devis, EntityManagerInterface $em)
    {
        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }

        $em ->remove($Devis);
        $em ->flush();
        return $this->redirectToRoute('app_devis'); 
    }
}
