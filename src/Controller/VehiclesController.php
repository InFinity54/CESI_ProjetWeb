<?php
namespace App\Controller;

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

        return $this->render('content/vehicles/index.html.twig', [
            "vehicles" => $vehicles
        ]);
    }

    /**
     * @Route("/vehicles/disabled", name="vehicles_list_disabled")
     */
    public function vehiclesListDisabled()
    {
        $manager = $this->getDoctrine()->getManager();
        $vehicles = $manager->getRepository(Vehicle::class)->findBy(["isActivated" => false]);

        return $this->render('content/vehicles/disabled.html.twig', [
            "vehicles" => $vehicles
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
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id);

        if ($vehicle)
        {
            $vehicle->setIsActivated(true);
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
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id);

        if ($vehicle)
        {
            $vehicle->setIsActivated(false);
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
        return $this->render('content/vehicles/add.html.twig', []);
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
        $vehicle->setPhotos([]);
        $vehicle->setAgence($manager->getRepository(Agence::class)->find(1));
        $vehicle->setIsActivated(true);

        $manager->persist($vehicle);
        $manager->flush();

        $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getNumberplate());

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
        return $this->render('content/vehicles/addphoto.html.twig', []);
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
            $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getId());

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
        
        if ($vehicle)
        {
            return $this->render('content/vehicles/view.html.twig', [
                "vehicle" => $vehicle
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
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id);

        if ($vehicle)
        {
            return $this->render('content/vehicles/edit.html.twig', [
                "vehicle" => $vehicle
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
        $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getId());

        if ($vehicle)
        {
            $vehicle->setBrand($request->request->get("marque"));
            $vehicle->setModel($request->request->get("modele"));
            $vehicle->setManufactureDate(new DateTime($request->request->get("datefabrication")));
            $vehicle->setHeight($request->request->get("hauteur"));
            $vehicle->setWidth($request->request->get("largeur"));
            $vehicle->setWeight($request->request->get("poids"));
            $vehicle->setPower($request->request->get("puissance"));

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
