<?php

namespace App\Controller;

use App\Form\FilterType;
use App\Repository\CampusRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(TripRepository $tripRepository, CampusRepository $campusRepository): Response
    {
        $trips = $tripRepository->findAll();
        $campus = $campusRepository->findAll();

        $form = $this->createForm(FilterType::class);

        return $this->render('main/home.html.twig', [
            'trips' => $trips,
            'campusList' => $campus,
            'form' => $form->createView()
            ]);
    }





}
