<?php
namespace App\Controller;

use App\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage()
    {
        if (!$this->getUser())
        {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $vehiclesTotal = $manager->getRepository(Vehicle::class)->count([]);
        $vehicles = $manager->getRepository(Vehicle::class)->getMostRecentVehicles();

        return $this->render('content/index.html.twig', [
            "vehicles" => [
                "count" => $vehiclesTotal,
                "list" => $vehicles
            ]
        ]);
    }
}
