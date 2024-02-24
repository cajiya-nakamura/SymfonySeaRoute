<?php

namespace App\Controller;

use App\Entity\Route as RouteEntity;
use App\Form\RouteType;
use App\Repository\RouteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/edit/route')]
class RouteController extends AbstractController
{
    #[Route('/', name: 'app_route_index', methods: ['GET'])]
    public function index(RouteRepository $routeRepository): Response
    {
        return $this->render('route/index.html.twig', [
            'routes' => $routeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_route_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $route = new RouteEntity();
        $form = $this->createForm(RouteType::class, $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // depart_portとarrival_portが同じ場合は登録しない
            if ($route->getDepartPort() === $route->getArrivalPort()) {
                $this->addFlash('danger', '出発地と到着地が同じです');
                return $this->redirectToRoute('app_route_new', [], Response::HTTP_SEE_OTHER);
            }

            // 既に同じdepart_portとarrival_portのペアが登録済みの場合は登録しない
            $routeRepository = $entityManager->getRepository(RouteEntity::class);
            $existRoute = $routeRepository->findOneBy([
                'depart_port' => $route->getDepartPort(),
                'arrival_port' => $route->getArrivalPort()
            ]);
            if ($existRoute) {
                $this->addFlash('danger', '既に同じ出発地と到着地の組み合わせが登録されています');
                return $this->redirectToRoute('app_route_new', [], Response::HTTP_SEE_OTHER);
            }

            $entityManager->persist($route);
            $entityManager->flush();            
            
            return $this->redirectToRoute('app_route_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('route/new.html.twig', [
            'route' => $route,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_route_show', methods: ['GET'])]
    public function show(RouteEntity $route): Response
    {
        return $this->render('route/show.html.twig', [
            'route' => $route,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_route_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RouteEntity $route, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RouteType::class, $route);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_route_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('route/edit.html.twig', [
            'route' => $route,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_route_delete', methods: ['POST'])]
    public function delete(Request $request, RouteEntity $route, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$route->getId(), $request->request->get('_token'))) {
            $entityManager->remove($route);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_route_index', [], Response::HTTP_SEE_OTHER);
    }
}
