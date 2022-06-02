<?php

namespace App\Controller;

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

        $trips = $tripRepository->findAll();
        $campus = $campusRepository->findAll();
        $filter = new Filter();

        $form = $this->createForm(FilterType::class, $filter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            dump($filter);
            $trips = $tripRepository->filter($filter, $user, $stateRepository);
        }

        return $this->render('main/home.html.twig', [
            'trips' => $trips,
            'campusList' => $campus,
            'form' => $form->createView()
            ]);
    }
    #[Route('/legal-stuff', name: 'legal_stuff')]
    public function legalStuff(): Response
    {
        return $this->render('main/legal-stuff.html.twig');
    }

}
