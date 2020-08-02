<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("/", name="login")
     */
    public function login()
    {
        return $this->render('content/login/index.html.twig', []);
    }

    /**
     * @Route("/login", name="login_submit")
     */
    public function loginSubmit()
    {
        $this->addFlash("success", "Vous êtes désormais connecté(e).");
        return $this->redirectToRoute("homepage");
    }
}
