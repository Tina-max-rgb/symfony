<?php

namespace App\Controller;

use App\Entity\PartnerO;
use App\Form\PartnerOType;
use App\Repository\PartnerORepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/partner/o')]
class PartnerOController extends AbstractController
{
    #[Route('/', name: 'app_partner_o_index', methods: ['GET'])]
    public function index(PartnerORepository $partnerORepository): Response
    {
        return $this->render('partner_o/index.html.twig', [
            'partner_os' => $partnerORepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_partner_o_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PartnerORepository $partnerORepository): Response
    {
        $partnerO = new PartnerO();
        $form = $this->createForm(PartnerOType::class, $partnerO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerORepository->add($partnerO, true);

            return $this->redirectToRoute('app_partner_o_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner_o/new.html.twig', [
            'partner_o' => $partnerO,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partner_o_show', methods: ['GET'])]
    public function show(PartnerO $partnerO): Response
    {
        return $this->render('partner_o/show.html.twig', [
            'partner_o' => $partnerO,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_partner_o_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, PartnerO $partnerO, PartnerORepository $partnerORepository): Response
    {
        $form = $this->createForm(PartnerOType::class, $partnerO);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $partnerORepository->add($partnerO, true);

            return $this->redirectToRoute('app_partner_o_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('partner_o/edit.html.twig', [
            'partner_o' => $partnerO,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_partner_o_delete', methods: ['POST'])]
    public function delete(Request $request, PartnerO $partnerO, PartnerORepository $partnerORepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partnerO->getId(), $request->request->get('_token'))) {
            $partnerORepository->remove($partnerO, true);
        }

        return $this->redirectToRoute('app_partner_o_index', [], Response::HTTP_SEE_OTHER);
    }
}
