<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Form\AuthUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\AuthUserRepository;

class SecurityController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function login(Request $request, AuthUserRepository $authUserRepository)
    {   
        $user = new AuthUser(); 
        $form = $this->createForm(AuthUserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){ 

            $formData = $form->getData();

            $submittedPassword = $formData->getPassword();
            $submittedUsername = $formData->getUsername();

            print_r($submittedUsername);
    
            $existingUser = $authUserRepository->findOneBy(['username' => $submittedUsername]);
    
            if ($existingUser && password_verify($submittedPassword, $existingUser->getPassword())) {
                // Authentification réussie, rediriger vers une autre page
                return $this->redirectToRoute('app_users');
            } else {
                // Authentification échouée, afficher un message d'erreur
                $this->addFlash('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
            
            }
            
        }
        return $this->render('security/login.html.twig',[
            'form'=>$form->createView()
        ]);
    }
}
