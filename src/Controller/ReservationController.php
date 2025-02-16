<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Form\ReservationType;
use App\Repository\EventRepository;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Security;

final class ReservationController extends AbstractController{
    #[Route('/reservation', name: 'app_reservation')]
    public function index(): Response
    {
        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
        ]);
    }

    #[Route('/AddReservation/{id}', name: 'AddReservation')]
    public function AddReservation(EventRepository $s,ManagerRegistry $doctrine, Request $request, $id, Security $security): Response
    {
        $r=new Reservation();
        $e=$s->find($id);
        $form=$this->createForm(ReservationType::class, $r);
        $form->handleRequest($request);
        $user = $security->getUser();
        $r->setStudent($user);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $r->setEventId($e);
            $r->setReservationStatus('Confirmed');
            $em= $doctrine->getManager();
            $em->persist($r);
            $em->flush();
            return $this->redirectToRoute('ListOfEvents');
        }
        return $this->render('reservation/AddReservation.html.twig', [
            'form' => $form , 'event' => $e]);
    }
    #[Route('/EventReservations/{id}', name: 'EventReservations')]
    public function EventReservations(ReservationRepository $repo, $id,EntityManagerInterface $em, EventRepository $s): Response
    {
        $e=$s->find($id);
        $result=$repo->findall();
        $req = $em->createQuery(
            "SELECT r
             FROM App\Entity\Reservation r
             WHERE r.event_id = :keyword "
        );
        $req->setParameter('keyword', $e);
        $result = $req->getResult();
        return $this->render('reservation/EventReservations.html.twig', [
            'reservations' => $result, 'event' => $e
        ]);
    }
    #[Route('/Reservation_List_Student', name: 'Reservation_List_Student')]
    public function List_Student_Application(ReservationRepository $reservationRepo, Security $security): Response
    {
        // Get the currently logged-in user
        $user = $security->getUser();

        // Fetch applications for the logged-in student
        $reservations = $reservationRepo->findBy(['student' => $user]);

        // Render the template with the student's applications
        return $this->render('reservation/Reservation_List_Student.html.twig', [
            'reservations' => $reservations,
        ]);
    }

    #[Route('/DeleteReservation/{idReservation}/{idEvent}', name: 'DeleteReservation')]
    public function DeleteReservation(ReservationRepository $s,$idReservation, $idEvent,ManagerRegistry $d, EventRepository $repoE)
    {
        $e=$s->find($idReservation);
        $event = $repoE->find($idEvent);
        $em= $d->getManager();
        $em->remove($e);
        $em->flush();
        return $this->redirectToRoute('EventReservations' , ['id' => $event->getId()]);
    }
   
    #[Route("/UpdateReservations/{idReservation}/{idEvent}", name: 'UpdateReservation')]
    public function UpdateReservations(Request $request, ReservationRepository $repoR, EventRepository $repoE, ManagerRegistry $doctrine, $idReservation, $idEvent): Response
    {
        $reservation = $repoR->find($idReservation);
        $event = $repoE->find($idEvent);
        
        if (!$reservation) {
            throw $this->createNotFoundException('Reservation not found');
        }
        
        $form = $this->createForm(ReservationType::class, $reservation);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $doctrine->getManager();
            $entityManager->flush();
            
            // Redirect to EventReservations with the required id parameter
            return $this->redirectToRoute('EventReservations', ['id' => $event->getId()]);
        }
        
        // Render the form if not submitted or not valid
        return $this->render('reservation/UpdateReservation.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
        ]);
    }

}
