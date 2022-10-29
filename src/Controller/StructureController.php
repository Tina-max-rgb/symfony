<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\StructureRepository;

#[Route('/structure', name: 'structure_')]
class StructureController extends AbstractController
{
    #[Route('', name: 'index')]
    public function index(StructureRepository $structure_repository): Response
    {
        /** @var User */
        $user = $this->getUser();
        return $this->render('structure/index.html.twig', [
            'structure' => $structure_repository->findOneBy(['user' => $user->getId()]),
        ]);

    }

}



