<?php
namespace App\Controller;

use App\Entity\Status;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class StatusController extends AbstractController
{
    /**
     * @Route("/status", name="status")
     */
    public function statusList()
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        return $this->render('content/status/index.html.twig', []);
    }

    /**
     * @Route("/status/remove/{id}", name="status_remove", options={"expose"=true})
     * @param int $id
     * @return RedirectResponse
     */
    public function statusRemove(int $id): ?RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();

        if ($manager->getRepository(Status::class)->find($id)) {
            $manager->remove($manager->getRepository(Status::class)->find($id));
            $manager->flush();

            $this->addFlash("success", "Le statut a bien été supprimé.");
            return $this->redirectToRoute("status");
        }

        $this->addFlash("danger", "Le statut n'existe plus.");
        return $this->redirectToRoute("status");
    }

    /**
     * @Route("/status/add", name="status_add")
     */
    public function statusAdd()
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        return $this->render('content/status/add.html.twig', []);
    }

    /**
     * @Route("/status/add/submit", name="status_add_submit")
     * @param Request $request
     * @return RedirectResponse
     */
    public function statusAddSubmit(Request $request): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();

        $status = new Status();
        $status->setName($request->request->get("nom"));
        $status->setColor($request->request->get("couleur"));

        $manager->persist($status);
        $manager->flush();

        $this->addFlash("success", "Le statut a bien été ajouté.");
        return $this->redirectToRoute("status");
    }
}

