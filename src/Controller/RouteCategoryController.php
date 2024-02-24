<?php

namespace App\Controller;

use App\Entity\RouteCategory;
use App\Form\RouteCategoryType;
use App\Repository\RouteCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/edit/route/category')]
class RouteCategoryController extends AbstractController
{
    #[Route('/', name: 'app_route_category_index', methods: ['GET'])]
    public function index(RouteCategoryRepository $routeCategoryRepository): Response
    {
        return $this->render('route_category/index.html.twig', [
            'route_categories' => $routeCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_route_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $routeCategory = new RouteCategory();
        $form = $this->createForm(RouteCategoryType::class, $routeCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($routeCategory);
            $entityManager->flush();

            return $this->redirectToRoute('app_route_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('route_category/new.html.twig', [
            'route_category' => $routeCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_route_category_show', methods: ['GET'])]
    public function show(RouteCategory $routeCategory): Response
    {
        return $this->render('route_category/show.html.twig', [
            'route_category' => $routeCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_route_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, RouteCategory $routeCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RouteCategoryType::class, $routeCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_route_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('route_category/edit.html.twig', [
            'route_category' => $routeCategory,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_route_category_delete', methods: ['POST'])]
    public function delete(Request $request, RouteCategory $routeCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$routeCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($routeCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_route_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
