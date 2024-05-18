<?php

namespace App\Controller;

use App\Entity\Poster;
use App\Form\Poster1Type;
use App\Repository\PosterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/poster/controller2')]
class PosterController2Controller extends AbstractController
{
    #[Route('/', name: 'app_poster_controller2_index', methods: ['GET'])]
    public function index(PosterRepository $posterRepository): Response
    {
        return $this->render('poster_controller2/index.html.twig', [
            'posters' => $posterRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_poster_controller2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $poster = new Poster();
        $form = $this->createForm(Poster1Type::class, $poster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($poster);
            $entityManager->flush();

            return $this->redirectToRoute('app_poster_controller2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('poster_controller2/new.html.twig', [
            'poster' => $poster,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_poster_controller2_show', methods: ['GET'])]
    public function show(Poster $poster): Response
    {
        return $this->render('poster_controller2/show.html.twig', [
            'poster' => $poster,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_poster_controller2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Poster $poster, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Poster1Type::class, $poster);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_poster_controller2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('poster_controller2/edit.html.twig', [
            'poster' => $poster,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_poster_controller2_delete', methods: ['POST'])]
    public function delete(Request $request, Poster $poster, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$poster->getId(), $request->request->get('_token'))) {
            $entityManager->remove($poster);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_poster_controller2_index', [], Response::HTTP_SEE_OTHER);
    }
}
