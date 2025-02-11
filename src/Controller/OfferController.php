<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

final class OfferController extends AbstractController
{
    #[Route('/offer', name: 'app_offer')]
    public function index(): Response
    {
        return $this->render('offer/index.html.twig', [
            'controller_name' => 'OfferController',
        ]);
    }

    
    #[Route('/Offer_Details/{id}/{title) ', name: 'Offer_Details')]
    public function List_Offer_Details(OfferRepository $r, $id)
    {
        $offer = $r->find($id);
        return $this->render('offer/Offer_Details.html.twig', [
            'offer' => $offer,
        ]);
    }


    #[Route('/Offer_List ', name: 'Offer_List')]
    public function List_Offer(OfferRepository $r)
    {
        $result=$r->findAll();
        return $this->render('offer/Offer_List.html.twig', [
            'offers' => $result,
        ]);
    }


    #[Route('/Add_Offer', name: 'Add_Offer')]
    public function Add_Offer(ManagerRegistry $mr, Request $req)
    {
        $New_Offer = new Offer();
        $form = $this->createForm(OfferType::class, $New_Offer);
        $form->handleRequest($req);
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Process the form submission
            $em = $mr->getManager();
            $em->persist($New_Offer);
            $em->flush();
    
            // Reset the form by creating a new empty instance of the form
            $New_Offer = new Offer(); 
            $form = $this->createForm(OfferType::class, $New_Offer);
            return $this->redirectToRoute('Offer_List');
        }
    
        return $this->render('offer/Add_Offer.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    #[Route('/Delete_Offer/{id}', name: 'Delete_Offer')]
    public function Delete_Offer(OfferRepository $r, ManagerRegistry $mr, $id)
    {
        $e=$r->find($id);
        $em= $mr->getManager();
        $em->remove($e);
        $em->flush();
        return $this->redirectToRoute('Offer_List');
    }


    #[Route("/Update_Offer/{id}", name: 'Update_Offer')]
    public function Update_Offer(Request $request, OfferRepository $r, ManagerRegistry $mr, $id): Response
    {
        // Find the offer by ID
        $offer = $r->find($id);
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }
    
        // Create form for the offer
        $form = $this->createForm(OfferType::class, $offer);
        $form->handleRequest($request);
    
        // Check if form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {
            // Persist the changes to the database
            $entityManager = $mr->getManager();
            $entityManager->flush();
    
            // Redirect to offer list after successful update
            return $this->redirectToRoute('Offer_List');
        }
    
        // Return the form view for unsuccessful submission
        return $this->render('offer/Update_Offer.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/Search_Offer', name: 'Search_Offer')]
    public function Search_Offer(EntityManagerInterface $em, Request $request, OfferRepository $repo): Response
    {
    $result = $repo->findAll(); 
    if ($request->isMethod('post')) {
        $value = $request->request->get('title'); 
        if ($value) { 
            $req = $em->createQuery(
                "SELECT o
                 FROM App\Entity\Offer o
                 WHERE o.title LIKE :keyword
                    OR o.description LIKE :keyword"
            );
            $req->setParameter('keyword', '%' . $value . '%');
            $result = $req->getResult();
        }
    }
    return $this->render('offer/Search_Offer.html.twig', ['offers' => $result]);
    }

    

    // #[Route('/Apply_Offer ', name: 'Apply_Offer')]
    // public function Apply_To_Offer(int $id): Response
    // {
    //     // For now, just render an empty page
    //     return $this->render('offer/Apply_Offer.html.twig', [
    //         'offerId' => $id,
    //     ]);
    // }
}
