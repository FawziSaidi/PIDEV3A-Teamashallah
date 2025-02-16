<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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
    
        $modifiedFields = [];
    
        $email = $request->request->get('email');
        if ($email && filter_var($email, FILTER_VALIDATE_EMAIL) && $email !== $user->getEmail()) {
            $user->setEmail($email);
            $modifiedFields[] = 'Email';
        }
    
        $firstName = $request->request->get('first_name');
        if ($firstName && $firstName !== $user->getFirstName()) {
            $user->setFirstName($firstName);
            $modifiedFields[] = 'First Name';
        }
    
        $lastName = $request->request->get('last_name');
        if ($lastName && $lastName !== $user->getLastName()) {
            $user->setLastName($lastName);
            $modifiedFields[] = 'Last Name';
        }
    
        $address = $request->request->get('address');
        if ($address && $address !== $user->getAdress()) {
            $user->setAdress($address);
            $modifiedFields[] = 'Address';
        }
    
        $phoneNumber = $request->request->get('phone_number');
        if ($phoneNumber && $phoneNumber !== $user->getPhoneNumber()) {
            $user->setPhoneNumber($phoneNumber);
            $modifiedFields[] = 'Phone Number';
        }
    
        $bio = $request->request->get('bio');
        if ($bio && $bio !== $user->getBio()) {
            $user->setBio($bio);
            $modifiedFields[] = 'Bio';
        }
    
        if (!empty($modifiedFields)) {
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully. Modified fields: ' . implode(', ', $modifiedFields));
        } else {
            $this->addFlash('info', 'No changes were made to your profile.');
        }

        return $this->redirectToRoute('profile');
    }


    #[Route('/user/edit/{id}', name: 'app_edit_user', methods: ['POST'])]
    public function editUser(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $user = $entityManager->getRepository(User::class)->find($id);
    
        if (!$user) {
            throw $this->createNotFoundException('User not found');
        }
    
        $user->setFirstName($request->request->get('firstName'));
        $user->setLastName($request->request->get('lastName'));
        $user->setEmail($request->request->get('email'));
        
        // Update roles
        $newRole = $request->request->get('role');
        $user->setRoles([$newRole]);
    
        $entityManager->flush();
    
        $this->addFlash('success', 'User updated successfully.');
    
        return $this->redirectToRoute('app_users_table');
    }

}