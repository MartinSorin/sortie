<?php

namespace App\Service;

use App\Entity\Trip;
use App\Repository\StateRepository;
use App\Repository\TripRepository;

class UpdateState{

    /**
     * @return Trip[] Returns an array of Trip objects
     */
    public function sorted(StateRepository $stateRepository, TripRepository $tripRepository, mixed $trips): array
    {
        $states = $stateRepository->findAll();
//        $cloture=$stateRepository->findOneBySomeField('Cloturee');
//        $historisee=$stateRepository->findOneBySomeField('Historisee');
//        $terminee=$stateRepository->findOneBySomeField('Terminee');
//        $encours=$stateRepository->findOneBySomeField('En cours');
//        $annulee=$stateRepository->findOneBySomeField('Annulee');
//        $ouverte=$stateRepository->findOneBySomeField('Ouverte');

        foreach ($states as $value){
            if ($value->getWording() == 'Cloturee'){
                $cloture = $value;
            }
            if ($value->getWording() == 'Historisee'){
                $historisee = $value;
            }
            if ($value->getWording() == 'Terminee'){
                $terminee = $value;
            }
            if ($value->getWording() == 'En cours'){
                $encours = $value;
            }
            if ($value->getWording() == 'Annulee'){
                $annulee = $value;
            }
            if ($value->getWording() == 'Ouverte'){
                $ouverte = $value;
            }
        }

        foreach ($trips as $value) {

            /**
             * @var $value Trip
             */
            $datefin0 = $value->getDateTimeStart();
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
                if ($dateNow > $value->getDateLimitInscription()) {
                    $value->setState($cloture);
                    $tripRepository->add($value, true);

                }
                if ($datefin <= $dateNow) {
                    $value->setState($terminee);
                    $tripRepository->add($value, true);
                }
                if ($dateNow > $value->getDateTimeStart() && $datefin > $dateNow) {
                    $value->setState($encours);
                    $tripRepository->add($value, true);
                }


            }
            if ($value->getState() == $encours) {
                if ($dateNow >= $datefin) {
                    $value->setState($terminee);
                    $tripRepository->add($value, true);
                }
            }
            if (($value->getState() == $cloture && $dateNow > $datefinHisto) || ($value->getState() == $annulee && $dateNow > $datefinHisto) || $datefinHisto <= $dateNow) {
                if ($datefinHisto >= $datefin) {
                    $value->setState($historisee);
                    $tripRepository->add($value, true);
                }

            }
            if ($value->getState() == $cloture && $value->getDateLimitInscription() < $dateNow) {
                if ($value->getIsRegistered()->count() < $value->getNbInscriptionsMax()) {
                    $value->setState($ouverte);
                    $tripRepository->add($value, true);
                }
            }
        }
        return $trips;
    }

}