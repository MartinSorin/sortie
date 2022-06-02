<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Trip;
use App\Form\TripCancelType;
use App\Form\TripType;
use App\Repository\ParticipantRepository;
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
                $state=$stateRepository->findOneBySomeField('En creation');
                $trip->setState($state);
                $tripRepository->add($trip, true);
            }

            if ($request->get('publish')){
                $state=$stateRepository->findOneBySomeField('Ouverte');
                $trip->setState($state);
                $tripRepository->add($trip, true);
            }
        }

        return $this->render('trip/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancel($id,TripRepository $repository, Request $request, StateRepository $stateRepository): Response
    {

        $trip =$repository->find($id);
        /**
         * @var Participant $user
         */
        $user = $this->getUser();
        $description = $trip->getInfoTrip();
        $form = $this->createForm(TripCancelType::class, $trip);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()){

            if ($trip->getOrganiser()== $user){


                $motif=$form['motif']->getData();
                $trip->setInfoTrip($description .' ANNULEE Motif: '.$motif);
                $state=$stateRepository->findOneBySomeField('Annulee');
                $trip->setState($state);
                $repository->add($trip,true);

                $this->addFlash("success", "Sortie annulée avec succès!");

            }

//            return $this->redirectToRoute('home');
        }

        return $this->render('trip/canceltrip.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/seeTrip/{id}', name: 'display')]
    public function display($id,TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);

        dump($trip);
        return $this->render('trip/see.html.twig', [
            'trip' => $trip,
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
