<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VehiculesController extends AbstractController
{
    /**
     * @Route("/cars", name="cars_list")
     */
    public function carsList()
    {
        return $this->render('content/cars/index.html.twig', []);
    }

    /**
     * @Route("/cars/add", name="cars_add")
     */
    public function carsAdd()
    {
        return $this->render('content/cars/add.html.twig', []);
    }
}
