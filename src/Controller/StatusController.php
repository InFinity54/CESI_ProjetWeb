<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Status;

class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status")
     */
    public function statusList()
    {
        if (!$this->getUser())
        {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }
        $status = $this->getDoctrine()->getRepository(Status::class)->findAll();
        return $this->render('content/status/index.html.twig', [
            "allstatus" => $status
        ]);
    }

    /**
     * @Route("/status/remove/{id}", name="status_remove")
     */
    public function statusRemove(int $id)
    {
        if (!$this->getUser())
        {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        if ($this->getDoctrine()->getRepository(Status::class)->find($id))
        {
            $this->getDoctrine()->getManager()->remove($this->getDoctrine()->getRepository(Status::class)->find($id));
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("success", "Le statut a bien été supprimé.");
            return $this->redirectToRoute("status");
        }
        else
        {
            $this->addFlash("danger", "Le statut n'existe plus.");
            return $this->redirectToRoute("status");
        }
    }


    /**
     * @Route("/status/add", name="status_add")
     */
    public function statusAdd()
    {
        if (!$this->getUser())
        {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }
        return $this->render('content/status/add.html.twig', []);
    }

    /**
     * @Route("/status/add/submit", name="status_add_submit")
     */
    public function statusAddSubmit(Request $request)
    {
        if (!$this->getUser())
        {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $status = new Status();
        $status->setName($request->request->get("nom"));
        $status->setColor($request->request->get("couleur"));
        $this->getDoctrine()->getManager()->persist($status);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash("success", "Le statut a bien été ajouté.");
        return $this->redirectToRoute("status");
    }
}

