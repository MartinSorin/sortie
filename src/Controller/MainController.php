<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\FilterType;
use App\Form\Model\Filter;
use App\Repository\CampusRepository;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(Request $request, TripRepository $tripRepository, CampusRepository $campusRepository, StateRepository $stateRepository): Response
    {
        $user = $this->getUser();
        $filter = new Filter();
        if (!$this->getUser()) {
            $this->addFlash("warning", "Authentification obligatoire!");
            return $this->redirectToRoute('app_login');
        }
        $trips = $tripRepository->filter($filter, $user, $stateRepository);
        $campus = $campusRepository->findAll();
        $cloture=$stateRepository->findOneBySomeField('Cloturee');
        $historisee=$stateRepository->findOneBySomeField('Historisee');
        $terminee=$stateRepository->findOneBySomeField('Terminee');
        $encours=$stateRepository->findOneBySomeField('En cours');
        $annulee=$stateRepository->findOneBySomeField('Annulee');
        $ouverte=$stateRepository->findOneBySomeField('Ouverte');

        foreach ($trips as $value) {

            /**
             * @var $value Trip
             */
            $datefin0= $value->getDateTimeStart();
            $datefin01 = clone $datefin0;
            $datefin = date_modify($datefin01, '+' . $value->getDuration() . ' minutes');
            $datefin1 = clone $datefin;
            $datefinHisto = date_modify($datefin1, '+1 month');
            $dateNow = new \DateTime('now');

            if ($value->getState() == $ouverte) {
                if ($value->getIsRegistered()->count() == $value->getNbInscriptionsMax()) {
                    $value->setState($cloture);
                    $tripRepository->add($value, true);
                }
                if ($dateNow > $value->getDateLimitInscription()){
                    $value->setState($cloture);
                    $tripRepository->add($value, true);

                }
                if ($datefin <= $dateNow) {
                    $value->setState($terminee);
                    $tripRepository->add($value,true);
                }
                if ($dateNow >$value->getDateTimeStart() && $datefin > $dateNow ) {
                    $value->setState($encours);
                    $tripRepository->add($value, true);
                }


            }
            if ($value->getState() == $encours){
                if ($dateNow >= $datefin) {
                    $value->setState($terminee);
                    $tripRepository->add($value,true);
                }
            }
            if (($value->getState() == $cloture && $dateNow>$datefinHisto) ||  ($value->getState() == $annulee && $dateNow>$datefinHisto) || $datefinHisto <= $dateNow){
                if ($datefinHisto >= $datefin) {
                    $value->setState($historisee);
                    $tripRepository->add($value, true);
                }

            }
            if ($value->getState()==$cloture && $value->getDateLimitInscription()<$dateNow){
                if ($value->getIsRegistered()->count() < $value->getNbInscriptionsMax()){
                    $value->setState($ouverte);
                    $tripRepository->add($value,true);
                }
            }

    }

        $form = $this->createForm(FilterType::class, $filter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $trips = $tripRepository->filter($filter, $user, $stateRepository);
        }

        return $this->render('main/home.html.twig', [
            'trips' => $trips,
            'campusList' => $campus,
            'form' => $form->createView(),
            'user' => $user
            ]);
    }
    #[Route('/legal-stuff', name: 'legal_stuff')]
    public function legalStuff(): Response
    {
        return $this->render('main/legal-stuff.html.twig');
    }

}
