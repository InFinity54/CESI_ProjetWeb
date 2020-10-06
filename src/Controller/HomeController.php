<?php
namespace App\Controller;

use App\Entity\Agent;
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

        $agentsTotal = $this->getDoctrine()->getManager()->getRepository(Agent::class)->count([]);
        $agents = $this->getDoctrine()->getManager()->getRepository(Agent::class)->getMostRecentAgents();

        return $this->render('content/index.html.twig', [
            "agents" => [
                "count" => $agentsTotal,
                "list" => $agents
            ]
        ]);
    }
}
