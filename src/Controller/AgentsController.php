<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AgentsController extends AbstractController
{
    /**
     * @Route("/agents", name="agents_list")
     */
    public function agentsList()
    {
        return $this->render('content/agents/index.html.twig', []);
    }

    /**
     * @Route("/agents/enable", name="agents_enable")
     */
    public function agentsEnable()
    {
        $this->addFlash("success", "L'agent a bien été activé.");
        return $this->redirectToRoute("agents_list");
    }

    /**
     * @Route("/agents/disable", name="agents_disable")
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
    public function agentsAddSubmit()
    {
        $this->addFlash("success", "L'agent a bien été ajouté.");
        return $this->redirectToRoute("agents_view");
    }

    /**
     * @Route("/agents/view", name="agents_view")
     */
    public function agentsView()
    {
        return $this->render('content/agents/view.html.twig', []);
    }

    /**
     * @Route("/agents/edit", name="agents_edit")
     */
    public function agentsEdit()
    {
        return $this->render('content/agents/edit.html.twig', []);
    }

    /**
     * @Route("/agents/edit/submit", name="agents_edit_submit")
     */
    public function agentsEditSubmit()
    {
        $this->addFlash("success", "L'agent a bien été modifié.");
        return $this->redirectToRoute("agents_view");
    }
}
