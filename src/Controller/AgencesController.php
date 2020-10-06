<?php
namespace App\Controller;

use App\Service\AgencePictureUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Agence;

class AgencesController extends AbstractController
{
    /**
     * @Route("/agences", name="agences_list")
     */
    public function agencesList()
    {
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findBy([]);
        return $this->render('content/agences/index.html.twig', ['agences' => $agences]);
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
    public function agencesAddSubmit(Request $request, AgencePictureUploader $imageUploader)
    {
        $agence = new Agence();
        $agence->setNomAg($request->request->get('nom'));
        $agence->setAdresseAg($request->request->get('adresse'));
        $agence->setComplementAg($request->request->get('adressecomp'));
        $agence->setCodepostalAg($request->request->get('codepostal'));
        $agence->setVilleAg($request->request->get('ville'));
        $agence->setNumero($request->request->get('tel'));
        $agence->setFaxAg($request->request->get('fax'));

        $this->getdoctrine()->getmanager()->persist($agence);
        $this->getdoctrine()->getmanager()->flush();

        $agenceimage = $imageUploader->upload($request->files->get("photo"), $agence->getId());

        if ($agenceimage)
        {
            $agence->setImageAg($agenceimage);
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo de l'agence.");
        }

        $this->getdoctrine()->getmanager()->persist($agence);
        $this->getdoctrine()->getmanager()->flush();
        
        $this->addFlash("success", "L'agence a bien été ajoutée.");
        return $this->redirectToRoute("agences_view", [
            "id" => $agence->getId()
        ]);
    }

    /**
     * @Route("/agences/view/{id}", name="agences_view")
     */
    public function agencesView(int $id)
    {
        $agence = $this->getDoctrine()->getRepository(Agence::class)->find($id);
        return $this->render('content/agences/view.html.twig', ['agence' => $agence]);
    }

    /**
     * @Route("/agences/edit/{id}", name="agences_edit")
     */
    public function agencesEdit(int $id)
    {
        $agence = $this->getDoctrine()->getRepository(Agence::class)->find($id);
        return $this->render('content/agences/edit.html.twig', ['agence' => $agence]);
    }

    /**
     * @Route("/agences/edit/{id}/submit", name="agences_edit_submit")
     */
    public function agencesEditSubmit(int $id, Request $request, AgencePictureUploader $imageUploader)
    {
        $agence = $this->getDoctrine()->getRepository(Agence::class)->find($id);
        $agenceimage = $imageUploader->upload($request->files->get("photo"), $id);

        $agence->setImageAg($agenceimage);
        $agence->setNomAg($request->request->get('nom'));
        $agence->setAdresseAg($request->request->get('adresse'));
        $agence->setComplementAg($request->request->get('adressecomp'));
        $agence->setCodepostalAg($request->request->get('codepostal'));
        $agence->setVilleAg($request->request->get('ville'));
        $agence->setNumero($request->request->get('tel'));
        $agence->setFaxAg($request->request->get('fax'));

        if ($agenceimage)
        {
            $agence->setImageAg($agenceimage);
        }
        else
        {
            $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo de l'agence.");
        }

        $this->getdoctrine()->getmanager()->persist($agence);
        $this->getdoctrine()->getmanager()->flush();

        $this->addFlash("success", "L'agence a bien été modifiée.");
        return $this->redirectToRoute("agences_view", ['id' => $id]);
    }
}
