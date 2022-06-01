<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use App\Repository\TripRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ParticipantType;


class ParticipantController extends AbstractController
{

    #[Route('/updateProfil', name: 'updateProfil')]
    public function updateProfil(Request $request, UserPasswordHasherInterface $userPasswordHasher, ParticipantRepository $repository): Response
    {

        $participant = $this->getUser();

        $participant->setPassword('');



        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participant->setActive(1);
            $participant->setRoles(['ROLE_ADMIN']);
            // encode the plain password
            $participant->setPassword(
                $userPasswordHasher->hashPassword(
                    $participant,
                    $form->get('password')->getData()
                )
            );

            $repository->add($participant, true);
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('participant/updateProfil.html.twig', [
            'form' => $form->createView(),
        ]);

    }


    #[Route('/myProfil/{id}', name: 'myProfil')]
    public function myProfil($id, ParticipantRepository $repo): Response
    {
        $participant = $repo->find($id);
        return $this->render('participant/myProfil.html.twig', [
            'participant' => $participant,

        ]);


    }



}