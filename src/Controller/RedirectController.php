<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RedirectController extends AbstractController
{
    #[Route('/panel', name: 'app_panel')]
    public function redirectPanel(): Response
    {
        $user = $this->getUser();

        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return $this->redirectToRoute('app_panel_admin');
        }

        if (in_array('ROLE_TRAINER', $user->getRoles())) {
            return $this->redirectToRoute('app_panel_trainer');
        }

        return $this->redirectToRoute('app_panel_client');
    }
}

