<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(): Response
    {

        $trip =new Trip();
        //récupérer le campus de l'utilisateur
        //$trip->setSiteOrganiser();

        $form = $this->createForm(TripType::class, $trip);

        return $this->render('trip/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
