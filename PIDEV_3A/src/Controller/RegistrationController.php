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

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->get('role')->getData();
            if (!in_array($role, ['ROLE_STUDENT', 'ROLE_TEACHER', 'ROLE_HRM_CLUB', 'ROLE_HRM_STAGE'])) {
                throw new AccessDeniedException('Invalid role selected.');
            }

            // Create the appropriate entity based on the role
            switch ($role) {
                case 'ROLE_STUDENT':
                    $user = new Student();
                    break;
                case 'ROLE_TEACHER':
                    $user = new Teacher();
                    break;
                case 'ROLE_HRM_CLUB':
                    $user = new HRMClub();
                    break;
                case 'ROLE_HRM_STAGE':
                    $user = new HRMStage();
                    break;
                default:
                    $user = new User();
            }

            $user->setEmail($form->get('email')->getData());
            $user->setRoles([$role]);

            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // Set the created_at field
            $user->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            $this->addFlash('success', 'Your account has been created successfully.');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}