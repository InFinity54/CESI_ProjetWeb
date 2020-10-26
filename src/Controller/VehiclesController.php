<?php
namespace App\Controller;

use App\Entity\Status;
use App\Entity\Agence;
use App\Entity\Vehicle;
use App\Entity\Historique;
use App\Service\VehiclePictureUploader;
use DateTime;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehiclesController extends AbstractController
{
    /**
     * @Route("/vehicles", name="vehicles_list")
     */
    public function vehiclesList(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findBy([], ["nom_ag" => "ASC"]);
        $brands = $manager->getRepository(Vehicle::class)->getBrands();
        $models = $manager->getRepository(Vehicle::class)->getModels();
        $minmanufacturedate = $manager->getRepository(Vehicle::class)->getOldestManufactureDate();
        $minweight = $manager->getRepository(Vehicle::class)->getLowestWeight();
        $maxweight = $manager->getRepository(Vehicle::class)->getHighestWeight();
        $minpower = $manager->getRepository(Vehicle::class)->getLowestPower();
        $maxpower = $manager->getRepository(Vehicle::class)->getHighestPower();
        $status = $this->getDoctrine()->getRepository(Status::class)->findBy([], ["name" => "ASC"]);

        return $this->render('content/vehicles/index.html.twig', [
            "agences" => $agences,
            "brands" => $brands,
            "models" => $models,
            "oldest_manufacture_date" => $minmanufacturedate,
            "weight" => [
                "min" => $minweight,
                "max" => $maxweight
            ],
            "power" => [
                "min" => $minpower,
                "max" => $maxpower
            ],
            "status" => $status
        ]);
    }

    /**
     * @Route("/vehicles/search", name="vehicles_list_search")
     * @param Request $request
     * @return Response
     */
    public function vehiclesListSearch(Request $request): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $filters = $request->request->all();
        $filters["weight"] = explode(",", $filters["weight"]);
        $filters["power"] = explode(",", $filters["power"]);

        $manager = $this->getDoctrine()->getManager();
        $vehicles = $manager->getRepository(Vehicle::class)->findVehicles($filters, true);
        $filter_agence = $manager->getRepository(Agence::class)->find($filters["agence"]);
        $filter_status = $manager->getRepository(Status::class)->find($filters["status"]);

        return $this->render('content/vehicles/search.html.twig', [
            "vehicles" => $vehicles,
            "filters" => $filters,
            "filtersDetails" => [
                "agence" => $filter_agence,
                "status" => $filter_status
            ]
        ]);
    }

    /**
     * @Route("/vehicles/disabled", name="vehicles_list_disabled")
     */
    public function vehiclesListDisabled(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findBy([], ["nom_ag" => "ASC"]);
        $brands = $manager->getRepository(Vehicle::class)->getBrands();
        $models = $manager->getRepository(Vehicle::class)->getModels();
        $minmanufacturedate = $manager->getRepository(Vehicle::class)->getOldestManufactureDate();
        $minweight = $manager->getRepository(Vehicle::class)->getLowestWeight();
        $maxweight = $manager->getRepository(Vehicle::class)->getHighestWeight();
        $minpower = $manager->getRepository(Vehicle::class)->getLowestPower();
        $maxpower = $manager->getRepository(Vehicle::class)->getHighestPower();
        $status = $this->getDoctrine()->getRepository(Status::class)->findBy([], ["name" => "ASC"]);

        return $this->render('content/vehicles/index.html.twig', [
            "agences" => $agences,
            "brands" => $brands,
            "models" => $models,
            "oldest_manufacture_date" => $minmanufacturedate,
            "weight" => [
                "min" => $minweight,
                "max" => $maxweight
            ],
            "power" => [
                "min" => $minpower,
                "max" => $maxpower
            ],
            "status" => $status
        ]);
    }

    /**
     * @Route("/vehicles/search", name="vehicles_list_disabled_search")
     * @param Request $request
     * @return Response
     */
    public function vehiclesListDisabledSearch(Request $request): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $filters = $request->request->all();
        $filters["weight"] = explode(",", $filters["weight"]);
        $filters["power"] = explode(",", $filters["power"]);

        $manager = $this->getDoctrine()->getManager();
        $vehicles = $manager->getRepository(Vehicle::class)->findVehicles($filters, false);
        $filter_agence = $manager->getRepository(Agence::class)->find($filters["agence"]);
        $filter_status = $manager->getRepository(Status::class)->find($filters["status"]);

        return $this->render('content/vehicles/search.html.twig', [
            "vehicles" => $vehicles,
            "filters" => $filters,
            "filtersDetails" => [
                "agence" => $filter_agence,
                "status" => $filter_status
            ]
        ]);
    }

    /**
     * @Route("/vehicles/enable/{id}", name="vehicles_enable", options={"expose"=true})
     * @param string $id
     * @return RedirectResponse
     */
    public function vehiclesEnable(string $id): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);

        if ($vehicle) {
            $historique = new Historique();
            $historique->setAgent($this->getUser());
            $historique->setVehicle($vehicle);
            $historique->setDateheureModif(new \DateTime('now'));
            $historique->setNatureModif("Activation du véhicule");
            $historique->setDescriptionModif("Le véhicule peut être louer, préter ou en démonstration.");
            $historique->setAncienneValeur("Désactivé");
            $historique->setNouvelleValeur("Activé");

            $manager->persist($historique);
            $manager->flush();

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
     * @Route("/vehicles/disable/{id}", name="vehicles_disable", options={"expose"=true})
     * @param string $id
     * @return RedirectResponse
     */
    public function vehiclesDisable(string $id): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);

        if ($vehicle) {
            $historique = new Historique();
            $historique->setAgent($this->getUser());
            $historique->setVehicle($vehicle);
            $historique->setDateheureModif(new \DateTime('now'));
            $historique->setNatureModif("Désactivation du véhicule");
            $historique->setDescriptionModif("Le véhicule ne peut plus être louer, préter ou en démonstration.");
            $historique->setAncienneValeur("Activé");
            $historique->setNouvelleValeur("Désactivé");

            $manager->persist($historique);
            $manager->flush();

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
    public function vehiclesAdd(): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

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
     * @param Request $request
     * @param VehiclePictureUploader $imageUploader
     * @return RedirectResponse
     * @throws Exception
     */
    public function vehiclesAddSubmit(Request $request, VehiclePictureUploader $imageUploader): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

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

        if ($request->files->get("photo") !== null) {
            $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getNumberplate(), count($vehicle->getPhotos()));

            if ($vehiclephoto) {
                $vehicle->setPhotos([$vehiclephoto]);
                $manager->persist($vehicle);
                $manager->flush();
            } else {
                $this->addFlash("warning", "Une erreur est survenue durant l'envoi de la photo du véhicule.");
            }
        }

        $historique = new Historique();
        $historique->setAgent($this->getUser());
        $historique->setVehicle($vehicle);
        $historique->setDateheureModif(new \DateTime('now'));
        $historique->setNatureModif("Ajout du véhicule");
        $historique->setDescriptionModif("Le véhicule a été ajouté. Il peut être louer, préter ou en démonstration.");

        $manager->persist($historique);
        $manager->flush();

        $this->addFlash("success", "Le véhicule a bien été ajouté.");

        return $this->redirectToRoute("vehicles_view", [
            "id" => $vehicle->getNumberplate()
        ]);
    }

    /**
     * @Route("/vehicles/{id}/photo", name="vehicles_addphoto")
     * @param string $id
     * @return Response
     */
    public function vehiclesAddPhoto(string $id): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        return $this->render('content/vehicles/addphoto.html.twig', [
            "vehicleid" => $id
        ]);
    }

    /**
     * @Route("/vehicles/{id}/photo/submit", name="vehicles_addphoto_submit")
     * @param Request $request
     * @param string $id
     * @param VehiclePictureUploader $imageUploader
     * @return RedirectResponse
     */
    public function vehiclesAddPhotoSubmit(Request $request, string $id, VehiclePictureUploader $imageUploader): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id);

        if ($vehicle) {
            if ($request->files->get("photo") !== null) {
                $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getNumberplate(), count($vehicle->getPhotos()) + 1);

                if ($vehiclephoto) {
                    $historique = new Historique();
                    $historique->setAgent($this->getUser());
                    $historique->setVehicle($vehicle);
                    $historique->setDateheureModif(new \DateTime('now'));
                    $historique->setNatureModif("Ajout de photo du véhicule");
                    $historique->setDescriptionModif("Une photo du véhicule a été ajoutée.");

                    $manager->persist($historique);
                    $manager->flush();

                    $photos = $vehicle->getPhotos();
                    $photos[] = $vehiclephoto;
                    $vehicle->setPhotos($photos);

                    $manager->persist($vehicle);
                    $manager->flush();

                    $this->addFlash("success", "La photo a bien été ajoutée au véhicule.");
                } else {
                    $this->addFlash("danger", "Une erreur est survenue durant l'envoi de la photo du véhicule.");
                }
            }
        }

        return $this->redirectToRoute("vehicles_view", [
            "id" => $vehicle->getNumberplate()
        ]);
    }

    /**
     * @Route("/vehicles/view/{id}", name="vehicles_view", options={"expose"=true})
     * @param string $id
     * @return RedirectResponse|Response
     */
    public function vehiclesView(string $id)
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $vehicle = $this->getDoctrine()->getRepository(Vehicle::class)->find($id);
        $agences = $this->getDoctrine()->getRepository(Agence::class)->findAll();

        if ($vehicle) {
            return $this->render('content/vehicles/view.html.twig', [
                "vehicle" => $vehicle,
                "agences" => $agences
            ]);
        }

        $this->addFlash("danger", "Le véhicule n'a pas été trouvé. Affichage impossible.");
        return $this->redirectToRoute("vehicles_list");
    }

    /**
     * @Route("/vehicles/edit/{id}", name="vehicles_edit", options={"expose"=true})
     * @param string $id
     * @return RedirectResponse|Response
     */
    public function vehiclesEdit(string $id)
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);
        $status = $manager->getRepository(Status::class)->findAll();
        $agences = $manager->getRepository(Agence::class)->findAll();

        if ($vehicle) {
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
     * @param string $id
     * @param Request $request
     * @param VehiclePictureUploader $imageUploader
     * @return RedirectResponse
     * @throws Exception
     */
    public function vehiclesEditSubmit(string $id, Request $request, VehiclePictureUploader $imageUploader): RedirectResponse
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $vehicle = $manager->getRepository(Vehicle::class)->find($id);

        if ($vehicle) {
            if ($request->request->get("marque") != $vehicle->getBrand()) {
                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("La marque de la voiture a été modifié.");
                $historique->setAncienneValeur($vehicle->getBrand());
                $historique->setNouvelleValeur($request->request->get("marque"));

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setBrand($request->request->get("marque"));
            }

            if ($request->request->get("modele") != $vehicle->getModel()) {
                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("Le modèle de la voiture a été modifié.");
                $historique->setAncienneValeur($vehicle->getModel());
                $historique->setNouvelleValeur($request->request->get("modele"));

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setModel($request->request->get("modele"));
            }

            if ($request->request->get("datefabrication") != $vehicle->getManufactureDate()->format("Y-m-d")) {
                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("La date de fabrication a été modifié.");
                $historique->setAncienneValeur($vehicle->getManufactureDate()->format("Y-m-d"));
                $historique->setNouvelleValeur($request->request->get("datefabrication"));

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setManufactureDate(new DateTime($request->request->get("datefabrication")));
            }

            if ($request->request->get("hauteur") != $vehicle->getHeight()) {
                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("La hauteur du véhicule a été modifié.");
                $historique->setAncienneValeur($vehicle->getHeight());
                $historique->setNouvelleValeur($request->request->get("hauteur"));

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setHeight($request->request->get("hauteur"));
            }

            if ($request->request->get("largeur") != $vehicle->getWidth()) {
                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("La largeur du véhicule a été modifié.");
                $historique->setAncienneValeur($vehicle->getWidth());
                $historique->setNouvelleValeur($request->request->get("largeur"));

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setWidth($request->request->get("largeur"));
            }

            if ($request->request->get("poids") != $vehicle->getWeight()) {
                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("Le poids du véhicule a été modifié.");
                $historique->setAncienneValeur($vehicle->getWeight());
                $historique->setNouvelleValeur($request->request->get("poids"));

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setWeight($request->request->get("poids"));
            }

            if ($request->request->get("puissance") != $vehicle->getPower()) {
                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("La puissance du véhicule a été modifié.");
                $historique->setAncienneValeur($vehicle->getPower());
                $historique->setNouvelleValeur($request->request->get("puissance"));

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setPower($request->request->get("puissance"));
            }

            if ($request->request->get("statut") != $vehicle->getStatus()->getId()) {
                $statut = $manager->getRepository(Status::class)->find($request->request->get("statut"));

                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("Le statut du véhicule a été modifié.");
                $historique->setAncienneValeur($vehicle->getStatus()->getName());
                $historique->setNouvelleValeur($statut->getName());

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setStatus($manager->getRepository(Status::class)->find($request->request->get("statut")));
            }

            if ($request->request->get("agence") != $vehicle->getAgence()->getId()) {
                $agencehisto = $manager->getRepository(Agence::class)->find($request->request->get("agence"));

                $historique = new Historique();
                $historique->setAgent($this->getUser());
                $historique->setVehicle($vehicle);
                $historique->setDateheureModif(new \DateTime('now'));
                $historique->setNatureModif("Modification");
                $historique->setDescriptionModif("L'agence où se situe le véhicule a été modifié.");
                $historique->setAncienneValeur($vehicle->getAgence()->getNomAg());
                $historique->setNouvelleValeur($agencehisto->getNomAg());

                $manager->persist($historique);
                $manager->flush();

                $vehicle->setAgence($manager->getRepository(Agence::class)->find($request->request->get("agence")));
            }

            if ($request->files->get("photo") !== null) {
                $vehiclephoto = $imageUploader->upload($request->files->get("photo"), $vehicle->getNumberplate(), count($vehicle->getPhotos()) + 1);

                if ($vehiclephoto) {
                    $historique = new Historique();
                    $historique->setAgent($this->getUser());
                    $historique->setVehicle($vehicle);
                    $historique->setDateheureModif(new \DateTime('now'));
                    $historique->setNatureModif("Modification des photos du véhicule");
                    $historique->setDescriptionModif("Les photos concernant le véhicule ont été modifiées.");

                    $manager->persist($historique);
                    $manager->flush();
                    $photos = $vehicle->getPhotos();
                    $photos[] = $vehiclephoto;
                    $vehicle->setPhotos($photos);
                    $manager->persist($vehicle);
                    $manager->flush();
                } else {
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

    /**
     * @Route("/vehicles/view/historique/{id}", name="vehicles_historique")
     * @param string $id
     * @return RedirectResponse|Response
     */
    public function vehiclesHistorique(string $id)
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Vous n'êtes pas authentifié.");
            return $this->redirectToRoute("login");
        }

        $manager = $this->getDoctrine()->getManager();
        $historique = $manager->getRepository(Historique::class)->find($id);

        if ($historique) {
            return $this->render('content/vehicles/viewHistorique.html.twig', [
                "historique" => $historique
            ]);
        }

        $this->addFlash("danger", "L'historique du véhicule n'a pas été trouvé. Affichage impossible.");
        return $this->redirectToRoute("vehicles_list");
    }
}
