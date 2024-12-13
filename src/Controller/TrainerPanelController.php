<?php

namespace App\Controller;

use App\Entity\Exercise;
use App\Form\ExerciseType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrainerPanelController extends AbstractController
{
    #[Route('/panel/trainer', name: 'app_panel_trainer')]
    public function trainerPanel(Request $request, EntityManagerInterface $em): Response
    {
        $this->denyAccessUnlessGranted('ROLE_TRAINER');

        $message = null;

        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');

            // Znajdź użytkownika po e-mailu
            $client = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if (!$client) {
                $message = 'Użytkownik o podanym e-mailu nie istnieje.';
            } elseif (!in_array('ROLE_CLIENT', $client->getRoles())) {
                $message = 'Podany użytkownik nie jest klientem.';
            } elseif ($client->getAddedByTrainer() !== null) {
                $message = 'Ten klient jest już przypisany do innego trenera.';
            } else {
                $client->setAddedByTrainer($this->getUser());
                $em->flush();
                $message = 'Klient został pomyślnie przypisany.';
            }
        }
	 // Logika formularza dodawania ćwiczeń
        $exercise = new Exercise();
        $form = $this->createForm(ExerciseType::class, $exercise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($exercise);
            $em->flush();
            $this->addFlash('success', 'Ćwiczenie zostało dodane pomyślnie.');
            return $this->redirectToRoute('app_panel_trainer');
	}

        return $this->render('panel/trainer.html.twig', [
            'user' => $this->getUser(),
	    'message' => $message,
	    'form' => $form->createView(),
        ]);
    }
}

