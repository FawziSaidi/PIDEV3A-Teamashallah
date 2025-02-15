<?php

namespace App\Controller;

use App\Entity\Application;
use App\Form\ApplicationType;
use App\Repository\ApplicationRepository;
use App\Repository\OfferRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\File\File;

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


    #[Route('/Application_List_Student', name: 'Application_List_Student')]
    public function List_Student_Application(ApplicationRepository $applicationRepo, Security $security): Response
    {
        // Get the currently logged-in user
        $user = $security->getUser();

        // Fetch applications for the logged-in student
        $applications = $applicationRepo->findBy(['student' => $user]);

        // Render the template with the student's applications
        return $this->render('application/Application_List_Student.html.twig', [
            'applications' => $applications,
        ]);
    }


    #[Route('/Add_Application/{id}', name: 'Add_Application')]
    public function Add_Application(ManagerRegistry $mr, Request $req, OfferRepository $offerRepo, Security $security, $id,  #[Autowire('%cv_dir%')] string $cv_dir)
    {
        $offer = $offerRepo->find($id);
        // if (!$offer) {
        //     throw $this->createNotFoundException('Offer not found.');
        // }
        $New_Application = new Application();
        $New_Application->setOffer($offer);


        // Get the currently logged-in user
        $user = $security->getUser();

        // Set the user ID as the id_student in the application
        $New_Application->setStudent($user);

        $form = $this->createForm(ApplicationType::class, $New_Application);
        $form->handleRequest($req);

        if ($form->isSubmitted() && $form->isValid()) {
            // Handle the poster file upload
            $cv = $form['cv_path']->getData();
            if ($cv) {
                $file_name = uniqid() . '.' . $cv->guessExtension();
                $cv->move($cv_dir, $file_name);
                $New_Application->setCvPath($file_name);
            }

            // Process the form submission
            $em = $mr->getManager();
            $em->persist($New_Application);
            $em->flush();

            // Reset the form by creating a new empty instance of the form
            $New_Application = new Application();
            $form = $this->createForm(ApplicationType::class, $New_Application);


            // Redirect to the Offer_List route after successful submission
            return $this->redirectToRoute('Offer_List');
        }

        return $this->render('application/Add_Application.html.twig', [
            'form' => $form->createView(),
            'offer' => $offer,
        ]);
    }

    #[Route('/Delete_Application/{id}', name: 'Delete_Application')]
    public function Delete_Application(ApplicationRepository $r, ManagerRegistry $mr, $id)
    {
        $application = $r->find($id);
        $em = $mr->getManager();
        $em->remove($application);
        $em->flush();
        return $this->redirectToRoute('Application_List_Student');
    }


    #[Route("/Update_Application/{id}", name: 'Update_Application')]
    public function Update_Application(Request $request, ApplicationRepository $r, ManagerRegistry $mr, $id, #[Autowire('%cv_dir%')] string $cv_dir): Response
    {
        // Find the application by ID
        $application = $r->find($id);
        if (!$application) {
            throw $this->createNotFoundException('Application not found');
        }
        $offer=$application->getOffer();
        // Convert the cv_path string to a File object if it exists
        if ($application->getCvPath()) {
            $application->setCvPath(new File($cv_dir . '/' . $application->getCvPath()));
        }
    
        // Create form for the application
        $form = $this->createForm(ApplicationType::class, $application);
        $form->handleRequest($request);
    
        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            $cv = $form['cv_path']->getData();
            if ($cv) {
                $file_name = uniqid() . '.' . $cv->guessExtension();
                $cv->move($cv_dir, $file_name);
                $application->setCvPath($file_name);
            }
            // Persist the changes to the database
            $entityManager = $mr->getManager();
            $entityManager->flush();
    
            // Redirect to application list after successful update
            return $this->redirectToRoute('Application_List_Student');
        }
    
        // Return the form view for unsuccessful submission
        return $this->render('application/Update_Application.html.twig', [
            'form' => $form->createView(), 'offer' => $offer, // Pass the entire offer object
        ]);
    }

}
