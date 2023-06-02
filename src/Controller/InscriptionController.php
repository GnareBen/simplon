<?php

namespace App\Controller;

use App\Entity\Inscription;
use App\Form\InscriptionType;
use App\Repository\InscriptionRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription_index', methods: ['GET'])]
    public function index(InscriptionRepository $inscriptionRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $inscriptions = $paginator->paginate(
            $inscriptionRepository->findBy([], ['date_inscription' => 'DESC']),
            $request->query->getInt('page', 1),
            20
        );
        return $this->render('inscription/index.html.twig', [
            'inscriptions' => $inscriptions,
        ]);
    }

    #[Route('/nouvelle-inscription', name: 'app_inscription_new', methods: ['GET', 'POST'])]
    public function new(Request $request, InscriptionRepository $inscriptionRepository): Response
    {
        $inscription = new Inscription();
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscription->setDateInscription(new \DateTime());
            $inscriptionRepository->save($inscription, true);

            return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscription/new.html.twig', [
            'inscription' => $inscription,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edition', name: 'app_inscription_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Inscription $inscription, InscriptionRepository $inscriptionRepository): Response
    {
        $form = $this->createForm(InscriptionType::class, $inscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $inscriptionRepository->save($inscription, true);

            return $this->redirectToRoute('app_inscription_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('inscription/edit.html.twig', [
            'inscription' => $inscription,
            'form' => $form,
        ]);
    }
}
