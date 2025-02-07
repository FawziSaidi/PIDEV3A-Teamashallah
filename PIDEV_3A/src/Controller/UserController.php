<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;

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
}