<?php

namespace App\Controller;

use App\Entity\Structure;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PartnerRepository;

#[Route('/partenaire', name: 'partenaire_')]
class PartenaireController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(PartnerRepository $partner_repository): Response
    {
        /** @var User */
        $user = $this->getUser();
        return $this->render('partenaire/index.html.twig', [
            'partner' => $partner_repository->findOneBy(['user' => $user->getId()]),
        ]);

    }

    
    #[Route('/show-structure-permissions/{id}', name: 'show_structure_permissions')]
    public function showStructurePermissions(Structure $structure): Response
    {
        return $this->render('partenaire/liste_permissions.html.twig', [
            'structure' => $structure,
        ]);
    }
}



