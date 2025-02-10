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

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->get('role')->getData();
            $formUser = $form->getData();
    
            if (!in_array($role, ['ROLE_STUDENT', 'ROLE_TEACHER', 'ROLE_HRM_CLUB', 'ROLE_HRM_STAGE'])) {
                throw new AccessDeniedException('Invalid role selected.');
            }
    
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
                    $user->setEvent($form->get('event')->getData());
                    break;
                case 'ROLE_HRM_STAGE':
                    $user = new HRMStage();
                    $user->setCompany($form->get('company')->getData());
                    break;
                default:
                    $user = new User();
            }
    
            //Common fields to all users (any role.)
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
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload, normalement rien ne devrait se passer unless PHP bugs (pls don't)
                }

                $user->setAvatar($newFilename);
            }
    
            $entityManager->persist($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Your account has been created successfully.');
            return $this->redirectToRoute('homepage');
        }
    
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}