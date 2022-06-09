<?php

namespace App\Controller;

use App\Entity\Trip;
use App\Form\FilterType;
use App\Form\Model\Filter;
use App\Repository\CampusRepository;
use App\Repository\StateRepository;
use App\Repository\TripRepository;
use App\Service\UpdateState;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function home(Request $request, TripRepository $tripRepository, UpdateState $updateState, StateRepository $stateRepository): Response
    {
        $user = $this->getUser();
        $filter = new Filter();
        if (!$this->getUser()) {
            $this->addFlash("warning", "Authentification obligatoire!");
            return $this->redirectToRoute('app_login');
        }
        $trips = $tripRepository->filter($filter, $user, $stateRepository);
        //permet la mise à jour des états dans la base de donnée
        $tripsSorted = $updateState->sorted($stateRepository, $tripRepository, $trips);

        $form = $this->createForm(FilterType::class, $filter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $trips = $tripRepository->filter($filter, $user, $stateRepository);
            $tripsSorted = $updateState->sorted($stateRepository, $tripRepository, $trips);
        }

        return $this->render('main/home.html.twig', [
            'trips' => $tripsSorted,
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
