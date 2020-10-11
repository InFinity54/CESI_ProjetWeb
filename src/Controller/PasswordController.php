<?php
namespace App\Controller;

use App\Entity\Agent;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordController extends AbstractController
{
    private $passwordEncoder;
    private $vgestLogo;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/password", name="password")
     */
    public function password()
    {
        if ($this->getUser())
        {
            $this->addFlash("warning", "Vous êtes déjà connecté à votre compte. Vous pouvez modifier votre mot de passe depuis votre profil.");
            return $this->redirectToRoute('homepage');
        }

        return $this->render('content/password/index.html.twig', []);
    }

    /**
     * @Route("/password/submit", name="password_submit")
     */
    public function passwordSubmit(Request $request, Swift_Mailer $mailer)
    {
        if ($this->getUser())
        {
            $this->addFlash("warning", "Vous êtes déjà connecté à votre compte. Vous pouvez modifier votre mot de passe depuis votre profil.");
            return $this->redirectToRoute('homepage');
        }

        $manager = $this->getDoctrine()->getManager();
        $agent = $manager->getRepository(Agent::class)->findOneBy(["email" => $request->request->get("email")]);

        if ($agent)
        {
            $newPassword = $this->generatePassword();

            $agent->setPassword($this->passwordEncoder->encodePassword($agent, $newPassword));
            $manager->persist($agent);
            $manager->flush();

            $email = (new Swift_Message('Hello Email'))
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

            $this->addFlash("success", "Vous avez reçu un nouveau mot de passe à l'adresse e-mail saisie.");
            return $this->redirectToRoute("login");
        }

        $this->addFlash("danger", "Aucun compte possédant cette adresse e-mail n'a été trouvé.");
        return $this->redirectToRoute("password");
    }

    private function generatePassword($length = 10)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $count = mb_strlen($chars);

        for ($i = 0, $result = ''; $i < $length; $i++) {
            $index = random_int(0, $count - 1);
            $result .= mb_substr($chars, $index, 1);
        }

        return $result;
    }
}
