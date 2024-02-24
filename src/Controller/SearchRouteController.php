<?php

namespace App\Controller;

use App\Entity\Port;
use App\Repository\PortRepository;
use App\Repository\RouteRepository;
use App\Repository\ScheduleRepository;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Taniko\Dijkstra\Graph;


class SearchRouteController extends AbstractController
{

    #[Route('/route', name: 'app_route_top')]
    public function index(
        PortRepository $portRepository
    ): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $departPort = $_POST['depart_port'];
            $arrivalPort = $_POST['arrival_port'];
            if (!empty($departPort) && !empty($arrivalPort) && $departPort !== $arrivalPort) {
                return $this->redirectToRoute('app_search_route', [
                    'depart_port' => $departPort,
                    'arrival_port' => $arrivalPort
                ]);
            }
        }

        $ports = $portRepository->findAll();

        return $this->render('search_route/index.html.twig', [
            'controller_name' => 'SearchRouteController',
            'ports' => $ports
        ]);
    }

    #[Route('/route/{depart_port}/{arrival_port}', name: 'app_search_route', requirements: ['depart_port' => '\d+', 'arrival_port' => '\d+'])]
    public function search(
        Port $depart_port,
        Port $arrival_port,
        PortRepository $portRepository,
        RouteRepository $routeRepository,
        ScheduleRepository $scheduleRepository,
        LoggerInterface $logger
    ): Response
    {
        
        $routes = $routeRepository->findAll();
        // $routeの$depart_portと$arrival_port を取得

        // Create a new instance of the Graph class
        $graph = Graph::create();

        // Add routes to the graph
        foreach ($routes as $route) {
            $graph->add($route->getDepartPort()->getName(), $route->getArrivalPort()->getName(), 1 );
        }

        try {
            // Search for the shortest route using Dijkstra's algorithm
            $route = $graph->search($depart_port->getName(), $arrival_port->getName());
        } catch (\Exception $e) {

            $this->addFlash('danger', 'ルート情報が見つかりませんでした。');
            return $this->redirectToRoute('app_route_top', [], Response::HTTP_SEE_OTHER);

        }

        $routeBySection = [];
        for ($i = 0; $i < count($route) - 1; $i++) {
            
            $port1 = $portRepository->findBy([
                'name' => $route[$i]
            ]);
            $port2 = $portRepository->findBy([
                'name' => $route[$i + 1]
            ]);

            // Entityの配列（$routes）から、depart_port,arrival_portが特定の値のものを取得
            $thisRoute = $routeRepository->findOneBy([
                'depart_port' => $port1,
                'arrival_port' => $port2,
            ]);

            $routeBySection[] = [
                'section_name' => $route[$i] . '〜' . $route[$i + 1],
                'schedules' => $thisRoute->getSchedules(),
            ];
        }
        $logger->info('$routeBySection', $routeBySection);
        $logger->info('$routes', $routes);

        // Calculate the cost of the route
        // $cost = $graph->cost($route);

        return $this->render('search_route/search.html.twig', [
            'controller_name' => 'SearchRouteController',
            'route' => $route,
            'routeBySection' => $routeBySection,
            // 'cost' => $cost,
        ]);

    }
}
