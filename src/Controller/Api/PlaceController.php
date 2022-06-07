<?php

namespace App\Controller\Api;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    #[Route('/api/place/{id}', name: 'api_place')]
    public function index($id, PlaceRepository $placeRepository): JsonResponse
    {
        $place = $placeRepository->find($id);

        return $this->json(['street' => $place->getStreet(), 'postalCode' => $place->getCity()->getPostalCode(), 'latitude' => $place->getLatitude(), 'longitude' => $place->getLongitude()]);
    }
}
