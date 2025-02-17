<?php

namespace App\Controller;

use App\Entity\Thread;
use App\Entity\Reply;
use App\Repository\ThreadRepository;
use App\Repository\ForumRepository;
use App\Repository\ReplyRepository;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThreadController extends AbstractController
{
    
    #[Route('/{id}/like', name: 'app_thread_like', methods: ['POST'])]
    public function like(Thread $thread, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        if (!$thread->getLikes()->contains($user)) {
            $thread->addLike($user); // Assuming Thread entity has a method addLike()
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_thread_index');
    }

    
}
