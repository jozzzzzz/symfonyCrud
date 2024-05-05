<?php

namespace App\Controller;

use App\Entity\AuthUser;
use App\Form\AuthUserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AuthUserRepository;

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log(" . $output . ");</script>";
}

class SecurityController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function ajouterDevis(Request $request, AuthUserRepository $authUserRepository)
    {   
        $user = new AuthUser(); 
        $form = $this->createForm(AuthUserType::class, $user);
        $form->handleRequest($request);

        debug_to_console(('reo'));

        if($form->isSubmitted() && $form->isValid()){ 
            $submittedUsername = $form->get('username')->getData();
            $submittedPassword = $form->get('password')->getData();
    
            $existingUser = $authUserRepository->findOneBy(['username' => $submittedUsername]);
    
            if ($existingUser && password_verify($submittedPassword, $existingUser->getPassword())) {
                // Authentification réussie, rediriger vers une autre page
                return $this->redirectToRoute('app_users');
            } else {
                // Authentification échouée, afficher un message d'erreur
                $this->addFlash('error', 'Nom d\'utilisateur ou mot de passe incorrect.');
            
            }
        

            return $this->render('security/login.html.twig',[
                'form'=>$form->createView()
            ]);
        }
    }
}
