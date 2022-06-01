<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\ParticipantRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(): Response
    {

        $trip =new Trip();

        $user = $this->getUser();

        $form = $this->createForm(TripType::class, $trip, ['value' => $user]);


        if ($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('trip/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/remove/{id}', name: 'remove')]
    public function remove($id,TripRepository $repository): Response
    {

        $trip =$repository->find($id);

        $form = $this->createForm(TripType::class, $trip);


        if ($form->isSubmitted() && $form->isValid()){

        }

        return $this->render('trip/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
