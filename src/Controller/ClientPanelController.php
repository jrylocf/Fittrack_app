<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientPanelController extends AbstractController
{
    #[Route('/panel/client', name: 'app_panel_client')]
    public function clientPanel(): Response
    {
        $this->denyAccessUnlessGranted('ROLE_CLIENT');

        return $this->render('panel/client.html.twig', [
            'user' => $this->getUser(),
        ]);
    }
}

