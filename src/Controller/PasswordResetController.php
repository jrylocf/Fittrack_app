<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Uid\Uuid;

class PasswordResetController extends AbstractController
{
    #[Route('/reset-password', name: 'app_reset_password')]
    public function resetPasswordRequest(Request $request, EntityManagerInterface $em, MailerInterface $mailer): Response
    {
        if ($request->isMethod('POST')) {
            $emailInput = $request->request->get('email');

            $user = $em->getRepository(User::class)->findOneBy(['email' => $emailInput]);

            if ($user) {
                // Generowanie tokena i ustawienie daty wygaśnięcia
                $resetToken = Uuid::v4()->toRfc4122();
                $user->setResetToken($resetToken);
                $user->setResetTokenExpiresAt((new \DateTime())->modify('+1 hour'));

                $em->flush();

                // Wysłanie e-maila z linkiem
		$baseUrl = 'http://185.25.150.147:8000';

$email = (new Email())
    ->from('kuba@leebkydwdn.cfolks.pl')
    ->to($user->getEmail())
    ->subject('Resetowanie hasła')
    ->html('<p>Kliknij w poniższy link, aby zresetować swoje hasło:</p>
            <a href="' . $baseUrl . $this->generateUrl('app_reset_password_confirm', ['token' => $resetToken]) . '">Zresetuj hasło</a>');




                $mailer->send($email);

                $this->addFlash('success', 'Link do resetowania hasła został wysłany na podany adres e-mail.');
                return $this->redirectToRoute('app_login');
            }

            $this->addFlash('error', 'Nie znaleziono użytkownika z podanym adresem e-mail.');
        }

        return $this->render('password_reset/request.html.twig');
    }

    #[Route('/reset-password/confirm/{token}', name: 'app_reset_password_confirm')]
    public function resetPasswordConfirm(string $token, Request $request, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(User::class)->findOneBy(['resetToken' => $token]);

        if (!$user || $user->getResetTokenExpiresAt() < new \DateTime()) {
            $this->addFlash('error', 'Link do resetowania hasła jest nieprawidłowy lub wygasł.');
            return $this->redirectToRoute('app_login');
        }

        if ($request->isMethod('POST')) {
            $newPassword = $request->request->get('password');
            $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
            $user->setPassword($hashedPassword);

            // Wyczyść token
            $user->setResetToken(null);
            $user->setResetTokenExpiresAt(null);

            $em->flush();

            $this->addFlash('success', 'Twoje hasło zostało zresetowane.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password_reset/reset.html.twig', ['token' => $token]);
    }
}

