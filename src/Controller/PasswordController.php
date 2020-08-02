<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class PasswordController extends AbstractController
{
    /**
     * @Route("/password", name="password")
     */
    public function password()
    {
        return $this->render('content/password/index.html.twig', []);
    }

    /**
     * @Route("/password/submit", name="password_submit")
     */
    public function passwordSubmit()
    {
        $this->addFlash("success", "Vous avez reçu un nouveau mot de passe à l'adresse e-mail saisie.");
        return $this->redirectToRoute("login");
    }
}
