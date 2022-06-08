<?php

namespace App\Controller\Api;

use App\Repository\PlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PlaceController extends AbstractController
{
    #[Route('/api/place', name: 'api_place')]
    public function index(PlaceRepository $placeRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent());

        $place = $placeRepository->find($data->id);

        return $this->json(['street' => $place->getStreet(), 'postalCode' => $place->getCity()->getPostalCode(), 'latitude' => $place->getLatitude(), 'longitude' => $place->getLongitude()]);
    }
}
