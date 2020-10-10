<?php
namespace App\Controller;

use App\Entity\Agent;
use App\Service\AgentPictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function profile()
    {
        $user = $this->getDoctrine()->getRepository(Agent::class)->findBy(["username" => $this->getUser()->getUsername()])[0];
        //au dd($user);
        return $this->render('content/profile/view.html.twig', [
            "profile" => $user
        ]);
    }

    /**
     * @Route("/profile/edit", name="profile_edit")
     */
    public function profileEditProfil()
    {
        $user = $this->getDoctrine()->getRepository(Agent::class)->findBy(["username" => $this->getUser()->getUsername()])[0];
        return $this->render('content/profile/edit.html.twig', [
            "profile" => $user
        ]);
    }

    /**
     * @Route("/profile/edit/submit", name="profile_edit_submit")
     */
    public function profileEditSubmit(Request $request, AgentPictureUploader $imageUploader)
    {
        $user = $this->getDoctrine()->getRepository(Agent::class)->findBy(["username" => $this->getUser()->getUsername()])[0];
        $userphoto = $imageUploader->upload($request->files->get("photo"), $user->getId());

        $user->setLastname($request->request->get("nom"));
        $user->setFirstname($request->request->get("prenom"));
        $user->setFixe($request->request->get("fixe"));
        $user->setMobile($request->request->get("mobile"));
        $user->setFax($request->request->get("fax"));
        $user->setEmail($request->request->get("email"));

        if ($userphoto)
        {
            $user->setPhoto($userphoto);
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue durant l'envoi de votre photo de profil.");
        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash("success", "Votre profil a bien été modifié.");
        return $this->redirectToRoute("profile");
    }
}
