<?php

namespace App\Controller;

use App\Entity\Port;
use App\Form\PortType;
use App\Repository\PortRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/edit/port')]
class PortController extends AbstractController
{
    #[Route('/', name: 'app_port_index', methods: ['GET'])]
    public function index(PortRepository $portRepository): Response
    {
        return $this->render('port/index.html.twig', [
            'ports' => $portRepository->findAllDesc(),
        ]);
    }

    #[Route('/new', name: 'app_port_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $port = new Port();
        $form = $this->createForm(PortType::class, $port);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 既に同じnameのPortが登録済みの場合は登録しない
            $portRepository = $entityManager->getRepository(Port::class);
            $existPort = $portRepository->findOneBy([
                'name' => $port->getName()
            ]);
            if ($existPort) {
                $this->addFlash('danger', '既に同じ名前の港が登録されています');
                return $this->redirectToRoute('app_port_new', [], Response::HTTP_SEE_OTHER);
            }
            
            $entityManager->persist($port);
            $entityManager->flush();

            return $this->redirectToRoute('app_port_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('port/new.html.twig', [
            'port' => $port,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_port_show', methods: ['GET'])]
    public function show(Port $port): Response
    {
        return $this->render('port/show.html.twig', [
            'port' => $port,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_port_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Port $port, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PortType::class, $port);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_port_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('port/edit.html.twig', [
            'port' => $port,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_port_delete', methods: ['POST'])]
    public function delete(Request $request, Port $port, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$port->getId(), $request->request->get('_token'))) {
            $entityManager->remove($port);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_port_index', [], Response::HTTP_SEE_OTHER);
    }
}
