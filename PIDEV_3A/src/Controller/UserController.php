<?php

namespace App\Controller;

use App\Entity\Student; // Or whichever type of user we want
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Student(); // Create a Student instead of a generic User
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);

            $now = new \DateTimeImmutable();
            $user->setCreatedAt($now);
            $user->setModifiedAt($now);

            // Save the user
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'User registered successfully!');
            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/index.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}