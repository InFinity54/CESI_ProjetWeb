<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AgencesController extends AbstractController
{
    /**
     * @Route("/agences", name="agences_list")
     */
    public function agencesList()
    {
        return $this->render('content/agences/index.html.twig', []);
    }

    /**
     * @Route("/agences/add", name="agences_add")
     */
    public function agencesAdd()
    {
        return $this->render('content/agences/add.html.twig', []);
    }

    /**
     * @Route("/agences/add/submit", name="agences_add_submit")
     */
    public function agencesAddSubmit()
    {
        $this->addFlash("success", "L'agence a bien été ajoutée.");
        return $this->redirectToRoute("agences_view");
    }

    /**
     * @Route("/agences/view", name="agences_view")
     */
    public function agencesView()
    {
        return $this->render('content/agences/view.html.twig', []);
    }

    /**
     * @Route("/agences/edit", name="agences_edit")
     */
    public function agencesEdit()
    {
        return $this->render('content/agences/edit.html.twig', []);
    }

    /**
     * @Route("/agences/edit/submit", name="agences_edit_submit")
     */
    public function agencesEditSubmit()
    {
        $this->addFlash("success", "L'agence a bien été modifiée.");
        return $this->redirectToRoute("agences_view");
    }
}
