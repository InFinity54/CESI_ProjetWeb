<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status")
     */
    public function statusList()
    {
        return $this->render('content/status/index.html.twig', []);
    }

    /**
     * @Route("/status/remove", name="status_remove")
     */
    public function statusRemove()
    {
        $this->addFlash("success", "Le statut a bien été supprimé.");
        return $this->redirectToRoute("status");
    }

    
    /**
     * @Route("/status/add", name="status_add")
     */
    public function statusAdd()
    {
        return $this->render('content/status/add.html.twig', []);
    }

    /**
     * @Route("/status/add/submit", name="status_add_submit")
     */
    public function statusAddSubmit()
    {
        $this->addFlash("success", "Le statut a bien été ajouté.");
        return $this->redirectToRoute("status");
    }
}
