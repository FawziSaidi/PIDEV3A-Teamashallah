<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\HrmClub;
use App\Entity\HrmStage;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

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
    public function usersTable(UserRepository $userRepository): Response
    {
        $registrationForm = $this->createForm(RegistrationFormType::class);
        $users = $userRepository->findAll();

        return $this->render('dashboard/users_table.html.twig', [
            'users' => $users,
            'registrationForm' => $registrationForm->createView(),
        ]);
    }

    #[Route('/dashboard/users/add', name: 'app_user_add', methods: ['POST'])]
    public function addUser(Request $request): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $role = $data['role'];

            switch ($role) {
                case 'ROLE_STUDENT':
                    $user = new Student();
                    $user->setDateOfBirth($data['date_of_birth']);
                    $user->setYearOfStudy($data['year_of_study']);
                    $user->setCoursesEnrolled($data['courses_enrolled']);
                    $user->setCertifications($data['certifications']);
                    $user->setDiplomas($data['diplomas']);
                    break;
                case 'ROLE_TEACHER':
                    $user = new Teacher();
                    $user->setSpecialization($data['specialization']);
                    $user->setYearsOfExperience($data['years_of_experience']);
                    break;
                case 'ROLE_HRM_CLUB':
                    $user = new HrmClub();
                    $user->setEvent($data['event']);
                    break;
                case 'ROLE_HRM_STAGE':
                    $user = new HrmStage();
                    $user->setCompany($data['company']);
                    break;
                default:
                    $user = new User();
            }

            // Set common fields
            $user->setEmail($data['email']);
            $user->setPassword($this->passwordHasher->hashPassword($user, $data['plainPassword']));
            $user->setFirstName($data['first_name']);
            $user->setLastName($data['last_name']);
            $user->setBio($data['bio']);
            $user->setPhoneNumber($data['phone_number']);
            $user->setAdress($data['adress']);
            $user->setAvatar($data['avatar']);
            $user->setRoles([$role]);

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', 'User added successfully.');
            return $this->redirectToRoute('app_users_table');
        }
    }  
}