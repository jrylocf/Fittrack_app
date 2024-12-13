<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPanelController extends AbstractController
{
    #[Route('/panel/admin', name: 'app_panel_admin')]
    public function adminPanel(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('panel/admin.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}
