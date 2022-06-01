<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\TripType;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TripController extends AbstractController
{
    #[Route('/add', name: 'add')]
    public function add(Request $request, TripRepository $tripRepository, StateRepository $stateRepository): Response
    {

        $user = $this->getUser();

        $trip =new Trip();
        $trip->setOrganiser($user);
        $trip->addIsRegistered($user);

        $form = $this->createForm(TripType::class, $trip);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){
            if ($request->get('save')){
                $trip->setState($stateRepository->find(15));
                $tripRepository->add($trip, true);
            }

            if ($request->get('publish')){
                $trip->setState($stateRepository->find(16));
                $tripRepository->add($trip, true);
            }
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

    #[Route('/seeTrip/{id}', name: 'display')]
    public function display($id,TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);

        return $this->render('trip/see.html.twig', [
            'trip' => $trip
        ]);
    }



    #[Route('/modify/{id}', name: 'modify')]
    public function modify($id,TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);

        return $this->render('trip/modify.html.twig', [
            'trip' => $trip
        ]);
    }
}
