<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;


use App\Repository\PartnerORepository;
use App\Repository\StructureRepository;
use App\Repository\PermissionRepository;
class FonOController extends AbstractController
{
    #[Route('/fon/o', name: 'app_fon_o')]
    public function index ( PartnerORepository $partneroRepository, StructureRepository $structuresRepository, PermissionRepository $permissionRepository): Response 
       {
        return $this->render('fon_o/index.html.twig', [
            'controller_name' => 'FonOController',

       ]);

   }
   }


