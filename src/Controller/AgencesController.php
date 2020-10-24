<?php
namespace App\Controller;

use App\Entity\Agence;
use App\Entity\Vehicle;
use App\Service\AgencePictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AgencesController extends AbstractController
{
    /**
     * @Route("/agences", name="agences_list")
     */
    public function agencesList(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        return $this->render('content/agences/index.html.twig', []);
    }

    /**
     * @Route("/agences/add", name="agences_add")
     */
    public function agencesAdd(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        return $this->render('content/agences/add.html.twig', []);
    }

    /**
     * @Route("/agences/add/submit", name="agences_add_submit")
     * @param Request $request
     * @param AgencePictureUploader $imageUploader
     * @return RedirectResponse
     */
    public function agencesAddSubmit(Request $request, AgencePictureUploader $imageUploader): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();

        $agence = new Agence();
        $agence->setNomAg($request->request->get('nom'));
        $agence->setAdresseAg($request->request->get('adresse'));
        $agence->setComplementAg($request->request->get('adressecomp'));
        $agence->setCodepostalAg($request->request->get('codepostal'));
        $agence->setVilleAg($request->request->get('ville'));
        $agence->setNumero($request->request->get('telFull'));
        $agence->setFaxAg($request->request->get('faxFull'));

        $manager->persist($agence);
        $manager->flush();

        if ($request->files->get("photo")) {
            $agenceimage = $imageUploader->upload($request->files->get("photo"), $agence->getId());

            if ($agenceimage) {
                $agence->setImageAg($agenceimage);
                $manager->persist($agence);
                $manager->flush();
            } else {
                $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo de l'agence.");
            }
        }

        $this->addFlash("success", "L'agence a bien été ajoutée.");

        return $this->redirectToRoute("agences_view", [
            "id" => $agence->getId()
        ]);
    }

    /**
     * @Route("/agences/view/{id}", name="agences_view", options={"expose"=true})
     * @param int $id
     * @return Response
     */
    public function agencesView(int $id): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $agence = $manager->getRepository(Agence::class)->find($id);
        $vehicles = $manager->getRepository(Vehicle::class)->findByAgence([$id]);

        return $this->render('content/agences/view.html.twig', [
            'agence' => $agence,
            'vehicles' => $vehicles
        ]);
    }

    /**
     * @Route("/agences/edit/{id}", name="agences_edit", options={"expose"=true})
     * @param int $id
     * @return Response
     */
    public function agencesEdit(int $id): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $agence = $manager->getRepository(Agence::class)->find($id);

        return $this->render('content/agences/edit.html.twig', [
            'agence' => $agence
        ]);
    }

    /**
     * @Route("/agences/edit/{id}/submit", name="agences_edit_submit")
     * @param int $id
     * @param Request $request
     * @param AgencePictureUploader $imageUploader
     * @return RedirectResponse
     */
    public function agencesEditSubmit(int $id, Request $request, AgencePictureUploader $imageUploader): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $agence = $manager->getRepository(Agence::class)->find($id);

        if ($agence) {
            $agence->setNomAg($request->request->get('nom'));
            $agence->setAdresseAg($request->request->get('adresse'));
            $agence->setComplementAg($request->request->get('adressecomp'));
            $agence->setCodepostalAg($request->request->get('codepostal'));
            $agence->setVilleAg($request->request->get('ville'));
            $agence->setNumero($request->request->get('telFull'));
            $agence->setFaxAg($request->request->get('telFull'));

            if ($request->files->get("photo")) {
                $agenceimage = $imageUploader->upload($request->files->get("photo"), $id);

                if ($agenceimage) {
                    $agence->setImageAg($agenceimage);
                } else {
                    $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo de l'agence.");
                }
            }

            $manager->persist($agence);
            $manager->flush();

            $this->addFlash("success", "L'agence a bien été modifiée.");

            return $this->redirectToRoute("agences_view", [
                'id' => $id
            ]);
        }

        $this->addFlash("danger", "L'agence demandée n'existe pas. Modification impossible.");
        return $this->redirectToRoute("agences_list");
    }
}
