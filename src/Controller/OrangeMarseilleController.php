<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Structures;
use App\Entity\Permissions;
class OrangeMarseilleController extends AbstractController
{
    /**
     * @Route("/orange/marseille", name="app_orange_marseille")
     *  @param  int  $id  identifiant de la structure
     *  @return Response
     */

    public function index(): Response
    {
       
        return $this->render('structures/index.html.twig', [
            'controller_name' => 'StructuresController',
           
        ]);
    }


    /**
     * @param  int  $id  identifiant de la permission
     * @return Response
     * 
     */
public function show(EntityManagerInterface $permissions, int $id, $em): Response
{
    $permissions  = $em->getRepository(Permissions::class)->findBy(['id' => $id]);
    return $this->render('permissions/index.html.twig', [
        'permissions' => $permissions,
    ]);
}

}
