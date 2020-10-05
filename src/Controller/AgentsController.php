<?php
namespace App\Controller;

use App\Entity\Agent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AgentsController extends AbstractController
{
    /**
     * @Route("/agents", name="agents_list")
     */
    public function agentsList()
    {
        $agents = $this->getDoctrine()->getRepository(Agent::class)->findAll();
        return $this->render('content/agents/index.html.twig', [
            "allAgents" => $agents
        ]);
    }

    /**
     * @Route("/agents/enable/{id}", name="agents_enable")
     */
    public function agentsEnable()
    {
        $this->addFlash("success", "L'agent a bien été activé.");
        return $this->redirectToRoute("agents_list");
    }

    /**
     * @Route("/agents/disable/{id}", name="agents_disable")
     */
    public function agentsDisable()
    {
        $this->addFlash("success", "L'agent a bien été désactivé.");
        return $this->redirectToRoute("agents_disabled");
    }

    /**
     * @Route("/agents/disabled", name="agents_disabled")
     */
    public function agentsDisabled()
    {
        return $this->render('content/agents/disabled.html.twig', []);
    }

    /**
     * @Route("/agents/add", name="agents_add")
     */
    public function agentsAdd()
    {
        return $this->render('content/agents/add.html.twig', []);
    }

    /**
     * @Route("/agents/add/submit", name="agents_add_submit")
     */
    public function agentsAddSubmit(Request $request)
    {
        $agents = new Agent();
        $agents->setLastname($request->request->get("nom"));
        $agents->setFirstname($request->request->get("prenom"));
        $agents->setFixe($request->request->get("fixe"));
        $agents->setMobile($request->request->get("mobile"));
        $agents->setFax($request->request->get("fax"));
        $agents->setUsername($request->request->get("identifiant"));
        $agents->setPassword("test");
        $agents->setRoles(["ROLE_USER"]);
        $agents->setEmail($request->request->get("email"));
        $agents->setIsActivated(true);
        $this->getDoctrine()->getManager()->persist($agents);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash("success", "L'agent a bien été ajouté.");
        return $this->redirectToRoute("agents_view");
    }

    /**
     * @Route("/agents/view/{id}", name="agents_view")
     */
    public function agentsView(int $id)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        return $this->render('content/agents/view.html.twig', [
            "agent" => $agent
        ]);
    }

    /**
     * @Route("/agents/edit/{id}", name="agents_edit")
     */
    public function agentsEdit(int $id)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        return $this->render('content/agents/edit.html.twig', [
            "agent" => $agent
        ]);
    }

    /**
     * @Route("/agents/edit/{id}/submit", name="agents_edit_submit")
     */
    public function agentsEditSubmit(Request $request, int $id)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        $agent->setLastname($request->request->get("nom"));
        $agent->setFirstname($request->request->get("prenom"));
        $agent->setFixe($request->request->get("fixe"));
        $agent->setMobile($request->request->get("mobile"));
        $agent->setFax($request->request->get("fax"));
        $agent->setEmail($request->request->get("email"));
        $this->getDoctrine()->getManager()->persist($agent);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("success", "L'agent a bien été modifié.");
        return $this->redirectToRoute("agents_view", ["id" => $id]);
    }
}
