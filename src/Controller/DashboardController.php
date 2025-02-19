<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\HrmClub;
use App\Entity\HrmStage;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Repository\OfferRepository;
use App\Repository\EventRepository;
use App\Repository\ForumRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Repository\ApplicationRepository;
use App\Repository\ReservationRepository;

final class DashboardController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(): Response
    {
        return $this->render('dashboard/profile.html.twig', [
            'controller_name' => 'DashboardController',
        ]);
    }
    
    #[Route('/dashboard', name: 'app_dashboard')]
    public function dashboard(UserRepository $userRepository): Response
    {
        $newUsersCount = $userRepository->countUsersCreatedLast24Hours();
        $userIncrease = $userRepository->getUserIncreaseLastWeek();
        $lastUsers = $userRepository->findBy([], ['created_at' => 'DESC'], 4);

        return $this->render('dashboard/dashboard.html.twig', [
            'controller_name' => 'DashboardController',
            'new_users_count' => $newUsersCount,
            'totalUsers' => $userIncrease['totalUsers'],
            'newUsers' => $userIncrease['newUsers'],
            'increasePercentage' => $userIncrease['increasePercentage'],
            'last_users' => $lastUsers,
        ]);
    }


    #[Route('/dashboard/users', name: 'app_users_table')]
    public function usersTable(UserRepository $userRepository, Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->get('role')->getData();
    
            switch ($role) {
                case 'ROLE_STUDENT':
                    $user = new Student();
                    $user->setDateOfBirth($form->get('date_of_birth')->getData());
                    $user->setYearOfStudy($form->get('year_of_study')->getData());
                    $user->setCoursesEnrolled($form->get('courses_enrolled')->getData());
                    $user->setCertifications($form->get('certifications')->getData());
                    $user->setDiplomas($form->get('diplomas')->getData());
                    break;
                case 'ROLE_TEACHER':
                    $user = new Teacher();
                    $user->setSpecialization($form->get('specialization')->getData());
                    $user->setYearsOfExperience($form->get('years_of_experience')->getData());
                    break;
                case 'ROLE_HRM_CLUB':
                    $user = new HrmClub();
                    $user->setClub($form->get('event')->getData());
                    break;
                case 'ROLE_HRM_STAGE':
                    $user = new HrmStage();
                    $user->setCompany($form->get('company')->getData());
                    break;
                default:
                    $user = new User();
            }
    
            $user->setEmail($form->get('email')->getData());
            $user->setFirstName($form->get('first_name')->getData());
            $user->setLastName($form->get('last_name')->getData());
            $user->setRoles([$role]);
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new \DateTimeImmutable());
            $user->setPhoneNumber($form->get('phone_number')->getData());
            $user->setBio($form->get('bio')->getData());
            $user->setAdress($form->get('adress')->getData());
    
            $avatarFile = $form->get('avatar')->getData();
            if ($avatarFile) {
                $originalFilename = pathinfo($avatarFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatarFile->guessExtension();
    
                try {
                    $avatarFile->move(
                        $this->getParameter('avatars_directory'),
                        $newFilename
                    );
                    $user->setAvatar($newFilename);
                } catch (FileException $e) {
                    // Handle file upload error
                }
            }
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'User added successfully.');
    
            return $this->redirectToRoute('app_users_table');
        }
    
        $users = $userRepository->findAll();
    
        return $this->render('dashboard/users_table.html.twig', [
            'users' => $users,
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/dashboard/user/delete/{id}', name: 'app_delete_user')]
    public function deleteUser(int $id, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash('success', 'User deleted successfully.');

        return $this->redirectToRoute('app_users_table');
    }

    #[Route('/dashboard/offers', name: 'app_offers_table')]
    public function offersTable(OfferRepository $offerRepository): Response
    {
        $offers = $offerRepository->findAll();

        return $this->render('dashboard/offers_table.html.twig', [
            'offers' => $offers,
        ]);
    }  
    #[Route('/dashboard/offerDetail/{id}', name: 'Offer_Details_Dashboard')]
    public function offerDetails(int $id, OfferRepository $offerRepository, ApplicationRepository $applicationRepository): Response
    {
        $offer = $offerRepository->find($id);
    
        if (!$offer) {
            throw $this->createNotFoundException('Offer not found');
        }
    
        $applications = $applicationRepository->findBy(['offer' => $offer]);
    
        return $this->render('dashboard/offer_details.html.twig', [
            'offer' => $offer,
            'applications' => $applications,
        ]);
    }

    #[Route('/dashboard/events', name: 'app_events_management')]
    public function eventsManagement(EventRepository $eventRepository): Response
    {
        $events = $eventRepository->findAll();

        return $this->render('dashboard/events_table.html.twig', [
            'events' => $events,
        ]);
    } 

    #[Route('/dashboard/eventDetail/{id}', name: 'Event_Details_Dashboard')]
    public function eventDetails(int $id, EventRepository $eventRepository, ReservationRepository $reservationRepository): Response
    {
        $event = $eventRepository->find($id);
    
        if (!$event) {
            throw $this->createNotFoundException('Event not found');
        }
    
        $reservations = $reservationRepository->findBy(['event_id' => $event]);
    
        return $this->render('dashboard/event_details.html.twig', [
            'event' => $event,
            'reservations' => $reservations,
        ]);
    }


    #[Route('/dashboard/forums', name: 'app_forums_management')]
    public function forumsManagement(ForumRepository $forumRepository): Response
    {
        $events = $forumRepository->findAll();

        return $this->render('dashboard/forums_table.html.twig', [
            'forums' => $events,
        ]);
    } 
}