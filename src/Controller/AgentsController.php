<?php
namespace App\Controller;

use App\Entity\Agent;
use App\Service\AgentPictureUploader;
use App\Service\PasswordGenerator;
use App\Service\UsernameGenerator;
use DateTime;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AgentsController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/agents", name="agents_list")
     */
    public function agentsList()
    {
        $agents = $this->getDoctrine()->getRepository(Agent::class)->findBy(["isActivated"=>true]);
        return $this->render('content/agents/index.html.twig', [
            "allAgents" => $agents
        ]);
    }

    /**
     * @Route("/agents/enable/{id}", name="agents_enable", options={"expose"=true})
     */
    public function agentsEnable(int $id)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        $agent->setIsActivated(true);
        $this->getDoctrine()->getManager()->persist($agent);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("success", "L'agent a bien été activé.");
        return $this->redirectToRoute("agents_list");
    }

    /**
     * @Route("/agents/disable/{id}", name="agents_disable", options={"expose"=true})
     */
    public function agentsDisable(int $id)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        $agent->setIsActivated(false);
        $this->getDoctrine()->getManager()->persist($agent);
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("success", "L'agent a bien été désactivé.");
        return $this->redirectToRoute("agents_disabled");
    }

    /**
     * @Route("/agents/disabled", name="agents_disabled")
     */
    public function agentsDisabled()
    {
        $agents = $this->getDoctrine()->getRepository(Agent::class)->findBy(["isActivated"=>false]);
        return $this->render('content/agents/disabled.html.twig', [
            "allAgents" => $agents
        ]);
    }

    /**
     * @Route("/agents/add", name="agents_add")
     */
    public function agentsAdd()
    {
        return $this->render('content/agents/add.html.twig', []);
    }

    /**
     * @Route("/agents/add/submit", name="agents_add_submit")
     */
    public function agentsAddSubmit(Request $request, AgentPictureUploader $imageUploader, Swift_Mailer $mailer)
    {
        $username = UsernameGenerator::generate($request->request->get("prenom"), $request->request->get("nom"));
        $password = PasswordGenerator::generate();

        $agent = new Agent();
        $agent->setLastname($request->request->get("nom"));
        $agent->setFirstname($request->request->get("prenom"));
        $agent->setFixe($request->request->get("fixe"));
        $agent->setMobile($request->request->get("mobile"));
        $agent->setFax($request->request->get("fax"));
        $agent->setUsername($username);
        $agent->setPassword($password);
        $agent->setRoles(["ROLE_USER"]);
        $agent->setEmail($request->request->get("email"));
        $agent->setIsActivated(true);
        $agent->setDateInscription(new DateTime("now"));

        $this->getDoctrine()->getManager()->persist($agent);
        $this->getDoctrine()->getManager()->flush();

        $agentphoto = $imageUploader->upload($request->files->get("photo"), $agent->getId());

        if ($agentphoto)
        {
            $agent->setPhoto($agentphoto);
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo de l'agent.");
        }

        $email = (new Swift_Message())
            ->setFrom(["noreply@projetweb.infinity54.fr" => "VGest"])
            ->setTo([$agent->getEmail() => $agent->getFirstname()." ".strtoupper($agent->getLastname())])
            ->setSubject("Votre compte VGest")
            ->setBody(
                $this->renderView('emails/accountcreated.html.twig', [
                    "agent" => [
                        "fullname" => $agent->getFirstname()." ".strtoupper($agent->getLastname()),
                        "username" => $username
                    ],
                    "newpassword" => $password
                ]),
                'text/html'
            )
        ;
        $mailer->send($email);

        $this->addFlash("success", "L'agent a bien été ajouté.");
        return $this->redirectToRoute("agents_view", [
            "id" => $agent->getId()
        ]);
    }

    /**
     * @Route("/agents/view/{id}", name="agents_view", options={"expose"=true})
     */
    public function agentsView(int $id)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        return $this->render('content/agents/view.html.twig', [
            "agent" => $agent
        ]);
    }

    /**
     * @Route("/agents/edit/{id}", name="agents_edit", options={"expose"=true})
     */
    public function agentsEdit(int $id)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        return $this->render('content/agents/edit.html.twig', [
            "agent" => $agent
        ]);
    }

    /**
     * @Route("/agents/edit/{id}/submit", name="agents_edit_submit")
     */
    public function agentsEditSubmit(Request $request, int $id, AgentPictureUploader $imageUploader)
    {
        $agent = $this->getDoctrine()->getRepository(Agent::class)->find($id);
        $agentphoto = $imageUploader->upload($request->files->get("photo"), $id);

        if ($agent)
        {
            $agent->setLastname($request->request->get("nom"));
            $agent->setFirstname($request->request->get("prenom"));
            $agent->setFixe($request->request->get("fixe"));
            $agent->setMobile($request->request->get("mobile"));
            $agent->setFax($request->request->get("fax"));
            $agent->setEmail($request->request->get("email"));

            if ($agentphoto)
            {
                $agent->setPhoto($agentphoto);
            }
            else
            {
                $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo de l'agence.");
            }

            $this->getDoctrine()->getManager()->persist($agent);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash("success", "L'agent a bien été modifié.");
            return $this->redirectToRoute("agents_view", ["id" => $id]);
        }

        $this->addFlash("danger", "L'agent demandé n'existe pas. Modification impossible.");
        return $this->redirectToRoute("agents_list");
    }

    /**
     * @Route("/agents/password/{id}", name="agents_password")
     */
    public function agentsPassword(int $id, Swift_Mailer $mailer)
    {
        $manager = $this->getDoctrine()->getManager();
        $agent = $manager->getRepository(Agent::class)->find($id);

        if ($agent)
        {
            $newPassword = PasswordGenerator::generate();

            $agent->setPassword($this->passwordEncoder->encodePassword($agent, $newPassword));
            $manager->persist($agent);
            $manager->flush();

            $email = (new Swift_Message())
                ->setFrom(["noreply@projetweb.infinity54.fr" => "VGest"])
                ->setTo([$agent->getEmail() => $agent->getFirstname()." ".strtoupper($agent->getLastname())])
                ->setSubject("Réinitialisation de votre mot de passe")
                ->setBody(
                    $this->renderView('emails/forgotpassword.html.twig', [
                        "agent" => [
                            "fullname" => $agent->getFirstname()." ".strtoupper($agent->getLastname()),
                            "username" => $agent->getUsername()
                        ],
                        "newpassword" => $newPassword
                    ]),
                    'text/html'
                )
            ;
            $mailer->send($email);

            $this->addFlash("success", "Le mot de passe de l'agent a bien été réinitialisé.");
            return $this->redirectToRoute("agents_list");
        }

        $this->addFlash("danger", "L'agent n'existe plus. Réinitialisation du mot de passe impossible.");
        return $this->redirectToRoute("agents_list");
    }
}
