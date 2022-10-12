<?php

namespace App\Controller;

use App\Entity\U;
use App\Form\UType;
use App\Repository\URepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/u')]
class UController extends AbstractController
{
    #[Route('/', name: 'app_u_index', methods: ['GET'])]
    public function index(URepository $uRepository): Response
    {
        return $this->render('u/index.html.twig', [
            'us' => $uRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_u_new', methods: ['GET', 'POST'])]
    public function new(Request $request, URepository $uRepository): Response
    {
        $u = new U();
        $form = $this->createForm(UType::class, $u);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uRepository->add($u, true);

            return $this->redirectToRoute('app_u_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('u/new.html.twig', [
            'u' => $u,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_u_show', methods: ['GET'])]
    public function show(U $u): Response
    {
        return $this->render('u/show.html.twig', [
            'u' => $u,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_u_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, U $u, URepository $uRepository): Response
    {
        $form = $this->createForm(UType::class, $u);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $uRepository->add($u, true);

            return $this->redirectToRoute('app_u_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('u/edit.html.twig', [
            'u' => $u,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_u_delete', methods: ['POST'])]
    public function delete(Request $request, U $u, URepository $uRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$u->getId(), $request->request->get('_token'))) {
            $uRepository->remove($u, true);
        }

        return $this->redirectToRoute('app_u_index', [], Response::HTTP_SEE_OTHER);
    }
}
