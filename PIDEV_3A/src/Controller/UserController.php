<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    #[Route('/user/delete/{id}', name: 'user_delete')]
    public function deleteUser(int $id, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Check if the logged-in user is trying to delete their own account
        if ($security->getUser() && $security->getUser()->getId() === $user->getId()) {
            $entityManager->remove($user);
            $entityManager->flush();

            // Log out the user
            $this->container->get('security.token_storage')->setToken(null);

            $this->addFlash('success', 'Your account has been successfully deleted.');
            return $this->redirectToRoute('app_login');
        }

        $this->addFlash('error', 'You do not have permission to delete this account.');
        return $this->redirectToRoute('profile');
    }

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    #[Route("/profile", name: "profile")]
    public function profile(): Response
    {
        return $this->render('dashboard/profile.html.twig');
    }

    #[Route("/user/update/{id}", name: "user_update", methods: ["POST"])]
    public function updateUser(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);

        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }

        // Check if the current user is the same as the user being updated
        if ($this->security->getUser() !== $user) {
            throw $this->createAccessDeniedException('You can only update your own profile');
        }

        $email = $request->request->get('email');
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $user->setEmail($email);
            $entityManager->flush();
            $this->addFlash('success', 'Email updated successfully');
        } else {
            $this->addFlash('error', 'Invalid email address');
        }

        return $this->redirectToRoute('profile');
    }
}