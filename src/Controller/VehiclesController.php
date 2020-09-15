<?php
namespace App\Controller;

use App\Entity\Vehicle;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @Route("/vehicles/enable", name="vehicles_enable")
     */
    public function vehiclesEnable()
    {
        $this->addFlash("success", "Le véhicule a été activé.");
        return $this->redirectToRoute("vehicles_list");
    }

    /**
     * @Route("/vehicles/disable", name="vehicles_disable")
     */
    public function vehiclesDisable()
    {
        $this->addFlash("success", "Le véhicule a été désactivé.");
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
    public function vehiclesAddSubmit()
    {
        $this->addFlash("success", "Le véhicule a bien été ajouté.");
        return $this->redirectToRoute("vehicles_view");
    }

    /**
     * @Route("/vehicles/view", name="vehicles_view")
     */
    public function vehiclesView()
    {
        return $this->render('content/vehicles/view.html.twig', []);
    }

    /**
     * @Route("/vehicles/edit", name="vehicles_edit")
     */
    public function vehiclesEdit()
    {
        return $this->render('content/vehicles/edit.html.twig', []);
    }

    /**
     * @Route("/vehicles/edit/submit", name="vehicles_edit_submit")
     */
    public function vehiclesEditSubmit()
    {
        $this->addFlash("success", "Le véhicule a bien été modifié.");
        return $this->redirectToRoute("vehicles_view");
    }
}
