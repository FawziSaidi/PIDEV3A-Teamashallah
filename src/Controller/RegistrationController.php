<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\HRMClub;
use App\Entity\HRMStage;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\String\Slugger\SluggerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class RegistrationController extends AbstractController
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $this->logger->info('Registration process started');

        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $this->logger->info('Form submitted and valid');

            $role = $form->get('role')->getData();
            $formUser = $form->getData();
    
            if (!in_array($role, ['ROLE_STUDENT', 'ROLE_TEACHER', 'ROLE_HRM_CLUB', 'ROLE_HRM_STAGE'])) {
                $this->logger->error('Invalid role selected: ' . $role);
                throw new AccessDeniedException('Invalid role selected.');
            }
    
            $this->logger->info('Creating user with role: ' . $role);

            switch ($role) {
                case 'ROLE_STUDENT':
                    $user = new Student();
                    $user->setDateOfBirth($form->get('date_of_birth')->getData());
                    $user->setYearOfStudy($form->get('year_of_study')->getData());
                    
                    $coursesEnrolled = $form->get('courses_enrolled')->getData();
                    $user->setCoursesEnrolled($coursesEnrolled ? explode(',', $coursesEnrolled) : null);
                    
                    $certifications = $form->get('certifications')->getData();
                    $user->setCertifications($certifications ? explode(',', $certifications) : null);
                    
                    $diplomas = $form->get('diplomas')->getData();
                    $user->setDiplomas($diplomas ? explode(',', $diplomas) : null);
                    break;
                case 'ROLE_TEACHER':
                    $user = new Teacher();
                    $user->setSpecialization($form->get('specialization')->getData());
                    $user->setYearsOfExperience($form->get('years_of_experience')->getData());
                    break;
                case 'ROLE_HRM_CLUB':
                    $user = new HRMClub();
                    $user->setClub($form->get('club')->getData());
                    break;
                case 'ROLE_HRM_STAGE':
                    $user = new HRMStage();
                    $user->setCompany($form->get('company')->getData());
                    break;
                default:
                    $user = new User();
            }
    
            //Common fields to all users (any.)
            $user->setEmail($formUser->getEmail());
            $user->setFirstName($formUser->getFirstName());
            $user->setLastName($formUser->getLastName());
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
                    $this->logger->info('Avatar file uploaded successfully');
                } catch (FileException $e) {
                    $this->logger->error('Error uploading avatar file: ' . $e->getMessage());
                }
            }
    
            try {
                $entityManager->persist($user);
                $entityManager->flush();
                $this->logger->info('User persisted successfully: ' . $user->getId());
            } catch (\Exception $e) {
                $this->logger->error('Error persisting user: ' . $e->getMessage());
                throw $e;
            }
    
            $this->addFlash('success', 'Your account has been created successfully.');
            return $this->redirectToRoute('homepage');
        } elseif ($form->isSubmitted()) {
            $this->logger->warning('Form submitted but invalid');
            foreach ($form->getErrors(true) as $error) {
                $this->logger->warning($error->getMessage());
            }
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}