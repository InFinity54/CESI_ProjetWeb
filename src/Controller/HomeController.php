<?php
namespace App\Controller;

use App\Entity\Vehicle;
use App\Entity\Agent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $vehiclesTotal = $manager->getRepository(Vehicle::class)->count([]);
        $vehicles = $manager->getRepository(Vehicle::class)->getMostRecentVehicles();
        $agentsTotal = $this->getDoctrine()->getManager()->getRepository(Agent::class)->count([]);
        $agents = $this->getDoctrine()->getManager()->getRepository(Agent::class)->getMostRecentAgents();

        return $this->render('content/index.html.twig', [
            "vehicles" => [
                "count" => $vehiclesTotal,
                "list" => $vehicles
            ],
            "agents" => [
                "count" => $agentsTotal,
                "list" => $agents
            ]
        ]);
    }
}
