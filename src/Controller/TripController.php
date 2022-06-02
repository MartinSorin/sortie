<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Trip;
use App\Form\TripCancelType;
use App\Form\TripModifyType;
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
                $trip->setState($stateRepository->find($state));
                $tripRepository->add($trip, true);
                $this->addFlash("success", "Sortie Enregistrée avec succès!");
            }

            if ($request->get('publish')){
                $state=$stateRepository->findOneBySomeField('Ouverte');
                $trip->setState($stateRepository->find($state));
                $tripRepository->add($trip, true);
                $this->addFlash("success", "Sortie Publiée avec succès!");
            }

            return $this->redirectToRoute('home');

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

            return $this->redirectToRoute('home');
        }

        return $this->render('trip/canceltrip.html.twig', [
            'form' => $form->createView(),
            'id'=>$id,
            'trip'=>$trip
        ]);
    }

    #[Route('/seeTrip/{id}', name: 'display')]
    public function display($id,TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);

        return $this->render('trip/see.html.twig', [
            'trip' => $trip,
        ]);
    }
    #[Route('/register/{id}', name: 'register')]
    public function register($id,TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);
        $user = $this->getUser();
        $trip->addIsRegistered($user);
        $tripRepository->add($trip,true);
        $this->addFlash("success", "Inscrit avec succès!");
        return $this->redirectToRoute('home')
        ;
    }

    #[Route('/unsubscribe/{id}', name: 'unsubscribe')]
    public function unsubscribe($id,TripRepository $tripRepository): Response
    {
        $trip = $tripRepository->find($id);
        $user = $this->getUser();
        $tabregister = $trip->getIsRegistered();
        foreach ($tabregister as $value)
        {
            if ($value == $user){
                $trip->removeIsRegistered($user);
                $tripRepository->add($trip,true);
                $this->addFlash("success", "Désinscrit avec succès!");
                return $this->redirectToRoute('home')
                    ;
            }
        }
        $this->addFlash("warning", "Oups, quelque chose c'est mal passé!");
        return $this->redirectToRoute('home');

    }


    #[Route('/modify/{id}', name: 'modify')]
    public function modify($id,TripRepository $repository, Request $request, StateRepository $stateRepository): Response
    {

        $trip = $repository->find($id);
        $user = $this->getUser();
        $state = $stateRepository->findOneBySomeField('En creation');

        if ($trip->getState()->getId() == $state->getId() && $trip->getOrganiser() == $user)
        {
            $form = $this->createForm(TripModifyType::class, $trip);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {

                if ($request->get('save'))
                {
                    $state = $stateRepository->findOneBySomeField('En creation');
                    $trip->setState($stateRepository->find($state));
                    $repository->add($trip, true);
                    $this->addFlash("success", "Sortie Enregistée avec succès!");
                }

                if ($request->get('publish'))
                {
                    $state = $stateRepository->findOneBySomeField('Ouverte');
                    $trip->setState($stateRepository->find($state));
                    $repository->add($trip, true);
                    $this->addFlash("success", "Sortie publiée avec succès!");
                }

                if ($request->get('remove'))
                {
                    $repository->remove($trip, true);
                    $this->addFlash("success", "Sortie Supprimée avec succès!");
                }

                return $this->redirectToRoute('home');
            }

            return $this->render('trip/modify.html.twig', [
                'form' => $form->createView(),
                'id' => $id,
                'trip' => $trip
            ]);


        }
        $this->addFlash("warning", "La suppression de sortie ne peut se faire que par l'organisateur avant la publication de celle-ci");
        return $this->redirectToRoute('home');

    }
    #[Route('/publish/{id}', name: 'publish')]
    public function publish($id,TripRepository $tripRepository,StateRepository $stateRepository): Response
    {
        $trip = $tripRepository->find($id);
        $state = $stateRepository->findOneBySomeField('Ouverte');
        $trip->setState($state);
        $tripRepository->add($trip, true);
        $this->addFlash("success", "Sortie Publiée avec succès!");
        return $this->redirectToRoute('home');


    }


}
