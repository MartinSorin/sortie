<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ParticpantType;


class ParticipantController extends AbstractController
{

    #[Route('/updateProfil', name: 'updateProfil')]
    public function updateProfile(): Response
    {

        $participant = new Participant ();
        $participant->setName('');
        $participant->setFirstname('');
        $participant->setPhone('');
        $participant->setEmail('');
        $participant->setPassword('');


        $form = $this->createForm(ParticpantType::class, $participant);

        return $this->render('participant/updateProfil.html.twig', array(
            'form' => $form->createView(),
        ));
    }



}
