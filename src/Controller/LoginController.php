<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
            $roles =$this->getUser()->getRoles();
            
            if (in_array('ROLE_ADMIN',$roles, true)) {
                return $this->redirectToRoute('admin_index');
            } elseif (in_array('ROLE_PARTNER',$roles, true)) {
                return $this->redirectToRoute('partenaire_index');
            }

            return $this->redirectToRoute('structure_index');
         }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
