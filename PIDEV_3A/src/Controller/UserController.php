<?php

namespace App\Controller;

use App\Entity\Student;
use App\Entity\Teacher;
use App\Entity\Administrator;
use App\Entity\HRM;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/index', name: 'index')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $role = $form->get('role')->getData();
            $email = $form->get('email')->getData();
            $password = $form->get('password')->getData();

            // Create the appropriate user entity based on the selected role
            switch ($role) {
                case 'Student':
                    $user = new Student();
                    break;
                case 'Teacher':
                    $user = new Teacher();
                    break;
                case 'Administrator':
                    $user = new Administrator();
                    break;
                case 'HRM':
                    $user = new HRM();
                    break;
                default:
                    throw new \Exception('Invalid role selected');
            }

            $user->setEmail($email);
            $hashedPassword = $passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);

            $now = new \DateTimeImmutable();
            $user->setCreatedAt($now);
            $user->setModifiedAt($now);

            // Save the user
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User registered successfully!');
            return $this->redirectToRoute('index');
        }

        return $this->render('user/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}