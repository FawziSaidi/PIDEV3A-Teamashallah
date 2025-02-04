<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class HRMController extends AbstractController
{
    #[Route('/h/r/m', name: 'app_h_r_m')]
    public function index(): Response
    {
        return $this->render('hrm/index.html.twig', [
            'controller_name' => 'HRMController',
        ]);
    }
}
