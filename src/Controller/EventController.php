<?php

namespace App\Controller;

use App\Entity\Event;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class EventController extends AbstractController
{
    #[Route('/event', name: 'app_event')]
    public function index(): Response
    {
        return $this->render('event/index.html.twig', [
            'controller_name' => 'EventController',
        ]);
    }

    #[Route('/ListOfEvents', name: 'ListOfEvents')]
    public function ListOfEvents(EventRepository $repo): Response
    {
        $result=$repo->findall();
        return $this->render('event/ListOfEvents.html.twig', [
            'events' => $result,
        ]);
    }
    #[Route('/EventDetails/{id}', name: 'EventDetails')]
    public function EventDetails(EventRepository $s,$id): Response
    {
        $e=$s->find($id);
        return $this->render('event/EventDetails.html.twig', [
            'event' => $e,
        ]);
    }
    #[Route('/AddEvent', name: 'AddEvent')]
    public function AddEvent(ManagerRegistry $doctrine, Request $request, #[Autowire('%poster_dir%')] string $poster_dir): Response
    {
        $e=new Event();
        $form=$this->createForm(EventType::class, $e);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $photo=$form['poster']->getData();
            $file_name = uniqid().'.'.$photo->guessExtension();
            $photo->move($poster_dir,$file_name);
            $e->setPoster($file_name);
            $em= $doctrine->getManager();
            $em->persist($e);
            $em->flush();
            return $this->redirectToRoute('ListOfEvents');
        }
        return $this->render('event/AddEvent.html.twig', [
            'form' => $form]);
    }
    #[Route('/DeleteEvent/{id}', name: 'DeleteEvent')]
    public function DeleteEvent(EventRepository $s,$id,ManagerRegistry $d)
    {
        $e=$s->find($id);
        $em= $d->getManager();
        $em->remove($e);
        $em->flush();
        return $this->redirectToRoute('ListOfEvents');
    }

    #[Route("/UpdateEvent/{id}", name: 'UpdateEvent')]
public function UpdateEvent(Request $request, EventRepository $eventRepository, ManagerRegistry $doctrine, $id, #[Autowire('%poster_dir%')] string $poster_dir): Response
{
    
    $event = $eventRepository->find($id);
    if (!$event) {
        throw $this->createNotFoundException('Event not found');
    }
    $form = $this->createForm(EventType::class, $event);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $photo=$form['poster']->getData();

        if ($photo) {
            $newFilename = uniqid().'.'.$photo->guessExtension();
            $photo->move($poster_dir,$newFilename);
            $event->setPoster($newFilename);
        }

        $entityManager = $doctrine->getManager();
        $entityManager->flush();

        return $this->redirectToRoute('ListOfEvents');
    }

    
    return $this->render('event/UpdateEvent.html.twig', [
        'form' => $form->createView(),
    ]);
}

#[Route('/SearchEvent', name: 'SearchEvent')]
public function SearchEvent(EntityManagerInterface $em, Request $request, EventRepository $repo): Response
{
    $result = $repo->findAll(); 
    if ($request->isMethod('post')) {
        $value = $request->request->get('title'); 
        if ($value) { 
            $req = $em->createQuery(
                "SELECT e
                 FROM App\Entity\Event e
                 WHERE e.title LIKE :keyword
                    OR e.description LIKE :keyword
                    OR e.details LIKE :keyword"
            );
            $req->setParameter('keyword', '%' . $value . '%');
            $result = $req->getResult();
        }
    }
    return $this->render('event/SearchEvent.html.twig', ['events' => $result]);
}


}
