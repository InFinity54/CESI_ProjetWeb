<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function agentEditProfil()
    {
        return $this->render('content/profile/edit.html.twig', []);
    }

    /**
     * @Route("/profile/submit", name="profile_submit")
     */
    public function agentEditProfilSubmit()
    {
        $this->addFlash("success", "Le profil a bien été modifié.");
        return $this->redirectToRoute("profile");
    }
}
