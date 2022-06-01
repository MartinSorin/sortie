<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\CampusRepository;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ParticpantType;


class ParticipantController extends AbstractController
{

    #[Route('/updateProfil', name: 'updateProfil')]
    public function updateProfile(Request $request, UserPasswordHasherInterface $userPasswordHasher, ParticipantRepository $repository): Response
    {

        $participant = new Participant ();
//        $participant->setName('');
//        $participant->setFirstname('');
//        $participant->setPhone('');
//        $participant->setEmail('');
//        $participant->setPassword('');


        $form = $this->createForm(ParticpantType::class, $participant);
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

            $repository->add($participant,true);
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('participant/updateProfil.html.twig', array(
            'form' => $form->createView(),
        ));
    }



}
