<?php

namespace App\Controller;

use App\Entity\Command;
use App\Form\Command1Type;
use App\Repository\CommandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/command/controller2')]
class CommandController2Controller extends AbstractController
{
    #[Route('/', name: 'app_command_controller2_index', methods: ['GET'])]
    public function index(CommandRepository $commandRepository): Response
    {
        return $this->render('command_controller2/index.html.twig', [
            'commands' => $commandRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_command_controller2_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $command = new Command();
        $form = $this->createForm(Command1Type::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($command);
            $entityManager->flush();

            return $this->redirectToRoute('app_command_controller2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command_controller2/new.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_controller2_show', methods: ['GET'])]
    public function show(Command $command): Response
    {
        return $this->render('command_controller2/show.html.twig', [
            'command' => $command,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_command_controller2_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Command $command, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Command1Type::class, $command);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_command_controller2_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('command_controller2/edit.html.twig', [
            'command' => $command,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_command_controller2_delete', methods: ['POST'])]
    public function delete(Request $request, Command $command, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$command->getId(), $request->request->get('_token'))) {
            $entityManager->remove($command);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_command_controller2_index', [], Response::HTTP_SEE_OTHER);
    }
}
