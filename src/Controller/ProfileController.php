<?php
namespace App\Controller;

use App\Entity\Agent;
use App\Service\AgentPictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/profile", name="profile")
     */
    public function profile(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $user = $this->getDoctrine()->getRepository(Agent::class)->findBy(["username" => $this->getUser()->getUsername()])[0];

        return $this->render('content/profile/view.html.twig', [
            "profile" => $user
        ]);
    }

    /**
     * @Route("/profile/edit/submit", name="profile_edit_submit")
     * @param Request $request
     * @param AgentPictureUploader $imageUploader
     * @return RedirectResponse
     */
    public function profileEditSubmit(Request $request, AgentPictureUploader $imageUploader): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $user = $this->getDoctrine()->getRepository(Agent::class)->findBy(["username" => $this->getUser()->getUsername()])[0];

        $user->setLastname($request->request->get("nom"));
        $user->setFirstname($request->request->get("prenom"));
        $user->setFixe($request->request->get("fixe"));
        $user->setMobile($request->request->get("mobile"));
        $user->setFax($request->request->get("fax"));
        $user->setEmail($request->request->get("email"));

        if ($request->files->get("photo")) {
            $userphoto = $imageUploader->upload($request->files->get("photo"), $user->getId());

            if ($userphoto) {
                $user->setPhoto($userphoto);
            } else {
                $this->addFlash("warning", "Une erreur est survenue durant l'envoi de votre photo de profil.");
            }
        }

        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash("success", "Votre profil a bien été modifié.");
        return $this->redirectToRoute("profile");
    }

    /**
     * @Route("/profile/password", name="profile_password")
     */
    public function profilePassword(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        return $this->render('content/profile/password.html.twig', []);
    }

    /**
     * @Route("/profile/password/submit", name="profile_password_submit")
     * @param Request $request
     * @return RedirectResponse
     */
    public function profilePasswordSubmit(Request $request): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $user = $this->getDoctrine()->getRepository(Agent::class)->findBy(["username" => $this->getUser()->getUsername()])[0];
        $oldPassword = $request->request->get("oldpwd");
        $newPassword = $request->request->get("newpwd");

        if ($this->passwordEncoder->isPasswordValid($user, $oldPassword)) {
            $user->setPassword($this->passwordEncoder->encodePassword($user, $newPassword));

            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", "Votre mot de passe a bien été modifié.");
            return $this->redirectToRoute("profile");
        }

        $this->addFlash("danger", "Votre mot de passe actuel est incorrect.");
        return $this->redirectToRoute("profile_password");
    }
}
