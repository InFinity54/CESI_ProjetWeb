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
}
