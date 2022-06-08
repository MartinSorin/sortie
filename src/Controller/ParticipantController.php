<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ParticipantType;


class ParticipantController extends AbstractController
{

    #[Route('/updateProfil', name: 'updateProfil')]
    public function updateProfil(Request $request, UserPasswordHasherInterface $userPasswordHasher, ParticipantRepository $repository, FileUploader $fileUploader): Response
    {

        $participant = $this->getUser();
        //$participant->setPassword('');
        if (!$this->getUser()) {
            $this->addFlash("warning", "Authentification obligatoire!");
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ParticipantType::class, $participant);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $participant->setActive(1);
            $participant->setRoles(['ROLE_ADMIN']);


            if ($form->get('password')->getData()) {
                // encode the plain password
                $participant->setPassword(
                    $userPasswordHasher->hashPassword(
                        $participant,
                        $form->get('password')->getData()
                    )
                );

                $repository->add($participant, true);
            }
            $imageprofile = $form->get('imageProfile')->getData();
            if ($imageprofile) {
                $brochureFileName = $fileUploader->upload($imageprofile);
                $participant->setImageProfile($brochureFileName);
                $repository->add($participant, true);
            }



            // do anything else you need here, like send an email

             return $this->redirectToRoute('home');
        }

        return $this->render('participant/updateProfil.html.twig', [
            'form' => $form->createView(),
            'participant'=>$participant,
        ]);

    }


    #[Route('/myProfil/{id}', name: 'myProfil')]
    public function myProfil($id, ParticipantRepository $repo): Response
    {
        if (!$this->getUser()) {
            $this->addFlash("warning", "Authentification obligatoire!");
            return $this->redirectToRoute('app_login');
        }
        $participant = $repo->find($id);
        return $this->render('participant/myProfil.html.twig', [
            'participant' => $participant,

        ]);


    }


}