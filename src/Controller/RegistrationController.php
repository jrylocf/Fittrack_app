<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new User();

        // Formularz rejestracji bezpośrednio przez createForm
        $form = $this->createFormBuilder($user)
            ->add('name', TextType::class, ['label' => 'Imię'])
            ->add('email', EmailType::class, ['label' => 'E-mail'])
            ->add('password', PasswordType::class, ['label' => 'Hasło'])
            ->add('roles', ChoiceType::class, [
                'label' => 'Rola',
                'choices' => [
                    'Klient' => 'ROLE_CLIENT',
                    'Trener' => 'ROLE_TRAINER',
                    'Administrator' => 'ROLE_ADMIN',
                ],
                'multiple' => false,
                'expanded' => false,
                'mapped' => false, // Wyłącz automatyczne mapowanie
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Hashowanie hasła
            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            // Pobierz wartość roli i ustaw jako tablicę
            $roleData = $form->get('roles')->getData();
            $user->setRoles([$roleData]);

            // Zapis do bazy danych
            $entityManager->persist($user);
            $entityManager->flush();

            // Powiadomienie o sukcesie
            $this->addFlash('success', 'Rejestracja zakończona sukcesem!');
            return $this->redirectToRoute('app_login');
        }

        // Zwrócenie widoku formularza, jeśli nie jest poprawnie przetworzony
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}


