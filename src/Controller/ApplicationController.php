<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\ApplicationRepository;
use App\Repository\OfferRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ApplicationController extends AbstractController
{
    #[Route('/application', name: 'app_application')]
    public function index(): Response
    {
        return $this->render('application/index.html.twig', [
            'controller_name' => 'ApplicationController',
        ]);
    }

    #[Route('/Application_List/{id}/{title}', name: 'Application_List')]
    public function List_Application(ApplicationRepository $applicationRepo, OfferRepository $offerRepo, $id): Response
    {
        // Fetch the offer by its ID
        $offer = $offerRepo->find($id);
    
        // Fetch applications for the offer
        $applications = $applicationRepo->findBy(['offer' => $id]);
    
        // Render the template with both applications and the offer
        return $this->render('application/Application_List.html.twig', [
            'applications' => $applications,
            'offer' => $offer, // Pass the entire offer object
        ]);
    }

    #[Route('/Add_Application/{id}', name: 'Add_Application')]
    public function Add_Application(ManagerRegistry $mr, Request $req, OfferRepository $offerRepo, $id)
    {
        $offer = $offerRepo->find($id);
        // if (!$offer) {
        //     throw $this->createNotFoundException('Offer not found.');
        // }
        $New_Application = new Application();
        $New_Application->setOffer($offer);

        $form = $this->createForm(ApplicationType::class, $New_Application);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Process the form submission
            $em = $mr->getManager();
            $em->persist($New_Application);
            $em->flush();
    
            // Reset the form by creating a new empty instance of the form
            $New_Application = new Application(); 
            $form = $this->createForm(ApplicationType::class, $New_Application);
        }
    
        return $this->render('application/Add_Application.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
}
