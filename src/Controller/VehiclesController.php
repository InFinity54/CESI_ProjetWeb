<?php
namespace App\Controller;

use App\Entity\Status;
use App\Entity\Agence;
use App\Entity\Vehicle;
use App\Service\VehiclePictureUploader;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VehiclesController extends AbstractController
{
    /**
     * @Route("/vehicles", name="vehicles_list")
     */
    public function vehiclesList()
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicles = $manager->getRepository(Vehicle::class)->findBy(["isActivated" => true]);
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findAll();

        return $this->render('content/vehicles/index.html.twig', [
            "vehicles" => $vehicles,
            "agences" => $agences
        ]);
    }

    /**
     * @Route("/vehicles/disabled", name="vehicles_list_disabled")
     */
    public function vehiclesListDisabled()
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicles = $manager->getRepository(Vehicle::class)->findBy(["isActivated" => false]);
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findAll();

        return $this->render('content/vehicles/disabled.html.twig', [
            "vehicles" => $vehicles,
            "agences" => $agences
        ]);
    }

    /**
     * @Route("/vehicles/search", name="vehicles_list_search")
     */
    public function vehiclesListSearch()
    {
        return $this->render('content/vehicles/search.html.twig', []);
    }

    /**
     * @Route("/vehicles/enable/{id}", name="vehicles_enable")
     */
    public function vehiclesEnable(string $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);

        if ($vehicle)
        {
            $vehicle->setIsActivated(true);
            $manager->persist($vehicle);
            $manager->flush();
            $this->addFlash("success", "Le véhicule a été activé.");
            return $this->redirectToRoute("vehicles_list");
        }

        $this->addFlash("danger", "Le véhicule n'a pas été trouvé. Activation impossible.");
        return $this->redirectToRoute("vehicles_list");
    }

    /**
     * @Route("/vehicles/disable/{id}", name="vehicles_disable")
     */
    public function vehiclesDisable(string $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);

        if ($vehicle)
        {
            $vehicle->setIsActivated(false);
            $manager->persist($vehicle);
            $manager->flush();
            $this->addFlash("success", "Le véhicule a été désactivé.");
            return $this->redirectToRoute("vehicles_list");
        }

        $this->addFlash("danger", "Le véhicule n'a pas été trouvé. Désactivation impossible.");
        return $this->redirectToRoute("vehicles_list");
    }

    /**
     * @Route("/vehicles/add", name="vehicles_add")
     */
    public function vehiclesAdd()
    {
        $manager = $this->getDoctrine()->getManager();
        $status = $manager->getRepository(Status::class)->findAll();
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findAll();

        return $this->render('content/vehicles/add.html.twig', [
            "status" => $status,
            "agences" => $agences
        ]);
    }

    /**
     * @Route("/vehicles/add/submit", name="vehicles_add_submit")
     */
    public function vehiclesAddSubmit(Request $request, VehiclePictureUploader $imageUploader)
    {
        $manager = $this->getDoctrine()->getManager();

        $vehicle = new Vehicle();
        $vehicle->setNumberplate($request->request->get("immat"));
        $vehicle->setBrand($request->request->get("marque"));
        $vehicle->setModel($request->request->get("modele"));
        $vehicle->setManufactureDate(new DateTime($request->request->get("datefabrication")));
        $vehicle->setHeight($request->request->get("hauteur"));
        $vehicle->setWidth($request->request->get("largeur"));
        $vehicle->setWeight($request->request->get("poids"));
        $vehicle->setPower($request->request->get("puissance"));
        $vehicle->setStatus($manager->getRepository(Status::class)->find($request->request->get("statut")));
        $vehicle->setPhotos([]);
        $vehicle->setAgence($manager->getRepository(Agence::class)->find(1));
        $vehicle->setIsActivated(true);
        $vehicle->setAgence($manager->getRepository(Agence::class)->find($request->request->get("agence")));

        $manager->persist($vehicle);
        $manager->flush();

        if ($request->files->get("photo") !== null)
        {
            $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getNumberplate(), count($vehicle->getPhotos()));

            if ($vehiclephoto)
            {
                $vehicle->setPhotos([$vehiclephoto]);
                $manager->persist($vehicle);
                $manager->flush();
            }
            else
            {
                $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo du véhicule.");
            }
        }

        $this->addFlash("success", "Le véhicule a bien été ajouté.");
        return $this->redirectToRoute("vehicles_view", [
            "id" => $vehicle->getNumberplate()
        ]);
    }

    /**
     * @Route("/vehicles/{id}/photo", name="vehicles_addphoto")
     */
    public function vehiclesAddPhoto(string $id)
    {
        return $this->render('content/vehicles/addphoto.html.twig', [
            "vehicleid" => $id
        ]);
    }

    /**
     * @Route("/vehicles/{id}/photo/submit", name="vehicles_addphoto_submit")
     */
    public function vehiclesAddPhotoSubmit(Request $request, string $id, VehiclePictureUploader $imageUploader)
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id);

        if ($vehicle)
        {
            if ($request->files->get("photo") !== null)
            {
                $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getNumberplate(), count($vehicle->getPhotos()) + 1);

                if ($vehiclephoto)
                {
                    $photos = $vehicle->getPhotos();
                    $photos[] = $vehiclephoto;
                    $vehicle->setPhotos($photos);
                    $manager->persist($vehicle);
                    $manager->flush();
                    $this->addFlash("success", "La photo a bien été ajoutée au véhicule.");
                }
                else
                {
                    $this->addFlash("danger", "Une erreur est survenue durant l'envoi de la photo du véhicule.");
                }
            }
        }

        return $this->redirectToRoute("vehicles_view", [
            "id" => $vehicle->getNumberplate()
        ]);
    }

    /**
     * @Route("/vehicles/view/{id}", name="vehicles_view")
     */
    public function vehiclesView(string $id)
    {
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id);
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findAll();
        
        if ($vehicle)
        {
            return $this->render('content/vehicles/view.html.twig', [
                "vehicle" => $vehicle,
                "agences" => $agences
            ]);
        }

        $this->addFlash("danger", "Le véhicule n'a pas été trouvé. Affichage impossible.");
        return $this->redirectToRoute("vehicles_list");
    }

    /**
     * @Route("/vehicles/edit/{id}", name="vehicles_edit")
     */
    public function vehiclesEdit(string $id)
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);
        $status = $manager->getRepository(Status::class)->findAll();
        $agences = $manager->getRepository(Agence::class)->findAll();

        if ($vehicle)
        {
            return $this->render('content/vehicles/edit.html.twig', [
                "vehicle" => $vehicle,
                "status" => $status,
                "agences" => $agences
            ]);
        }

        $this->addFlash("danger", "Le véhicule n'a pas été trouvé. Édition impossible.");
        return $this->redirectToRoute("vehicles_list");
    }

    /**
     * @Route("/vehicles/edit/{id}/submit", name="vehicles_edit_submit")
     */
    public function vehiclesEditSubmit(string $id, Request $request, VehiclePictureUploader $imageUploader)
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findAll();

        if ($vehicle)
        {
            $vehicle->setBrand($request->request->get("marque"));
            $vehicle->setModel($request->request->get("modele"));
            $vehicle->setManufactureDate(new DateTime($request->request->get("datefabrication")));
            $vehicle->setHeight($request->request->get("hauteur"));
            $vehicle->setWidth($request->request->get("largeur"));
            $vehicle->setWeight($request->request->get("poids"));
            $vehicle->setPower($request->request->get("puissance"));
            $vehicle->setStatus($manager->getRepository(Status::class)->find($request->request->get("statut")));
            $vehicle->setAgence($manager->getRepository(Agence::class)->find($request->request->get("agence")));

            if ($request->files->get("photo") !== null)
            {
                $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getNumberplate(), count($vehicle->getPhotos()) + 1);

                if ($vehiclephoto)
                {
                    $photos = $vehicle->getPhotos();
                    $photos[] = $vehiclephoto;
                    $vehicle->setPhotos($photos);
                    $manager->persist($vehicle);
                    $manager->flush();
                }
                else
                {
                    $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo du véhicule.");
                }
            }

            $manager->persist($vehicle);
            $manager->flush();

            $this->addFlash("success", "Le véhicule a bien été modifié.");
            return $this->redirectToRoute("vehicles_view", [
                "id" => $vehicle->getNumberplate()
            ]);
        }

        $this->addFlash("danger", "Le véhicule n'a pas été trouvé. Édition impossible.");
        return $this->redirectToRoute("vehicles_list");
    }
}
