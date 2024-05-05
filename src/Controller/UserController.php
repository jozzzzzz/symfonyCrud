<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserStatusService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    private $statusService;

    public function __construct(UserStatusService $statusService) {
        $this->statusService = $statusService;
    }

    /*Fonction de recupération de tous les Users*/
    #[Route("/user",name: "app_users")]
    public function Users(UserRepository $UserRepository){

        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }

        $Users = $UserRepository->findAll();
        return $this->render('user/listesUsers.html.twig',array(
            'users'=>$Users
        ));
    }

    /*Fonction d'ajout d'un User*/
    #[Route('/ajouteruser', name: 'app_ajouter_user')]
    public function ajouterUser(Request $request, EntityManagerInterface $em)
    {
        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }

        $User = new User(); 
        $form = $this->createForm(UserType::class, $User);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){ 
            $em ->persist($User);
            $em ->flush();
            return $this->redirectToRoute('app_users');
        }
        return $this->render('user/ajouterUser.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /*Fonction de modification d'un User*/
    #[Route("/modifieruser/{id<\d+>}", name: "app_modifier_user")]
    public function modifierUser(Request $request, User $User, EntityManagerInterface $em)
    {
        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }

        $form = $this->createForm(UserType::class, $User);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->flush();
            return $this->redirectToRoute('app_users');
        }
        return $this->render('user/modifierUser.html.twig',array(
            'form'=>$form->createView()
        ));
    }

    /*Fonction de suppression d'un User*/
    #[Route("/supprimeruser/{id<\d+>}", name: "app_supprimer_user")]
    public function supprimerUser(User $User, EntityManagerInterface $em)
    {
        $status = $this->statusService->getStatus();

        if ($status == true) {
            return $this->render('unauthorized.html.twig');
        }

        $em ->remove($User);
        $em ->flush();
        return $this->redirectToRoute('app_users'); 
    }

}
