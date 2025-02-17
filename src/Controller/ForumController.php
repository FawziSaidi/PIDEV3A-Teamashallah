<?php
namespace App\Controller;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Repository\ForumRepository;
use App\Entity\Forum;
use App\Form\ForumFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Thread;
use App\Entity\Reply;
use App\Repository\ThreadRepository;
use App\Repository\ReplyRepository;

use Psr\Log\LoggerInterface;

use App\Repository\CommentRepository;
use Symfony\Component\Security\Core\Security;

final class ForumController extends AbstractController
{
    #[Route('/forum', name: 'forum_index', methods: ['GET', 'POST' ])]
    public function index(Request $request, ForumRepository $forumRepository, EntityManagerInterface $entityManager, Security $security): Response
    {
        $forums = $forumRepository->findAll();

        $forum = new Forum();
        $createForm = $this->createForm(ForumFormType::class, $forum);


        // Handle editing (if a forum is being updated)
        $editForm = $this->createForm(ForumFormType::class); 

        return $this->render('Forum/index.html.twig', [
            'forums' => $forums,
            'createForm' => $createForm->createView(),
            'editForm' => $editForm->createView(),
        ]);
    
    }

   
/*

    #[Route('/userforums', name: 'forum_indexuser', methods: ['GET', 'POST' ])]
    public function userForums(Request $request, ForumRepository $forumRepository, EntityManagerInterface $entityManager): Response
    {
        $forums = $forumRepository->findAll();

        return $this->render('forum/forumuser.html.twig', [
            'forums' => $forums,
         
        ]);
    
    }*/

    #[Route('/forum/create', name: 'forum_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager, Security $security): Response
    {
        $user = $security->getUser();
        $forum = new Forum();
        $forum->setAdmin($user);
        $form = $this->createForm(ForumFormType::class, $forum);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) { 
            $forum->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($forum);
            $entityManager->flush();
    
            return $this->json(['status' => 'success']);
        } else {
            $errors = [];
    
            // Collecting errors for each field
            foreach ($form->getErrors(true) as $error) {
                $fieldName = $error->getOrigin()->getName(); // Get the field name
                $errors[$fieldName] = $error->getMessage(); // Assign error to corresponding field
            }
    
            return $this->json([
                'status' => 'error',
                'errors' => $errors
            ]);
        }
    }
    
    
  
    
    #[Route('/forum/edit/{id}', name: 'forum_edit', methods: ['POST'])]
public function edit(Request $request, Forum $forum, EntityManagerInterface $entityManager): Response
{
    // here iam validating the incoming data
    $validator = Validation::createValidator();
    $constraints = new Assert\Collection([
        'titleForum' => [
            new Assert\NotBlank(['message' => 'Title cannot be empty']),
            new Assert\Length([
                'min' => 4,
                'minMessage' => 'Title must be at least 4 characters long'
            ])
        ],
        'descriptionForum' => [
            new Assert\NotBlank(['message' => 'Description cannot be empty']),
            new Assert\Length([
                'min' => 4,
                'minMessage' => 'Description must be at least 4 characters long'
            ])
        ]
    ]);

    // Validate all request data
    $violations = $validator->validate($request->request->all(), $constraints);

    // i need to filter violations to only include errors for the specific fields and constraints
    $filteredErrors = [];
    foreach ($violations as $violation) {
        $field = $violation->getPropertyPath(); 
        $message = $violation->getMessage();

        if (in_array(trim($field, '[]'), ['titleForum', 'descriptionForum'])) {
            $filteredErrors[] = $message;
        }
    }

    if (!empty($filteredErrors)) {
        return new JsonResponse([
            'status' => 'error',
            'message' => $filteredErrors
        ], 400);
    }

    $forum->setTitleForum($request->request->get('titleForum'));
    $forum->setDescriptionForum($request->request->get('descriptionForum'));
    $forum->setCategory($request->request->get('category'));
    $forum->setUpdatedAt(new \DateTimeImmutable());

    $entityManager->flush();

    return $this->json([
        'status' => 'success',
        'message' => 'Forum updated successfully'
    ]);
}




   /* #[Route('/forum/{id}', name: 'forum_show', methods: ['GET'])]
    public function show(Forum $forum): Response
    {
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
        ]);
    }*/

    #[Route('/forum/{id}', name: 'forum_delete', methods: ['POST'])]
    public function delete(Request $request, Forum $forum, ForumRepository $forumRepository, EntityManagerInterface $entityManager)
    {
        if ($this->isCsrfTokenValid('delete' . $forum->getId(), $request->request->get('_token'))) {
            $entityManager->remove($forum);
            $entityManager->flush();
    
        }
    
        $forums = $forumRepository->findAll();

$forum = new Forum();
$createForm = $this->createForm(ForumFormType::class, $forum);


$editForm = $this->createForm(ForumFormType::class); // Empty form for editing
 return $this->redirectToRoute('forum_index');

return 
$this->render('forum/index.html.twig', [
'forums' => $forums,
'createForm' => $createForm->createView(),
'editForm' => $editForm->createView(),
]);    }




// Thread and reply handling


#[Route('/user/thread/{id}', name: 'app_thread_index')]
    public function indexx(int $id,ThreadRepository $threadRepository): Response
    {
        $threads = $threadRepository->findBy(['forum' => $id]);

        return $this->render('thread/index.html.twig', [
            'threads' => $threads,
            'forum_id' => $id
            
        ]);
        
    }

    #[Route('/user/userforums', name: 'forum_indexuser', methods: ['GET', 'POST' ])]
    public function userForums(Request $request, ForumRepository $forumRepository, EntityManagerInterface $entityManager): Response
    {
        $forums = $forumRepository->findAll();

        return $this->render('forum/forumuser.html.twig', [
            'forums' => $forums,
         
        ]);
    
    }

    #[Route('/user/new', name: 'app_thread_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager, ForumRepository $forumRepository, Security $security): Response
{
    if ($request->isMethod('POST')) {
        $threadContent = $request->request->get('thread-content');
        $forumId = $request->request->get('forum_id'); // Add a hidden input for forum ID in the form
       // $user = $this->getUser(); // Ensure the user is logged in

    

        $forum = $forumRepository->find($forumId);
        if (!$forum) {
            throw $this->createNotFoundException('Forum not found.');
        }

        $thread = new Thread();
        $user = $security->getUser();
        $thread->setUser($user);
        $thread->setThreadContent($threadContent);
       // $thread->setUser($user); // Associate the thread with the logged-in user
        $thread->setForum($forum); // Associate the thread with the forum
        $thread->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($thread);
        $entityManager->flush();

        return $this->redirectToRoute('app_thread_index', ['id' => $forumId]);
    }

    return $this->render('thread/new.html.twig');
}


#[Route('/user/thread/{id}/reply', name: 'app_thread_reply', methods: ['POST'])]
public function addReply(Request $request, Thread $thread, EntityManagerInterface $entityManager , LoggerInterface $logger, Security $security): Response
{
    $content = $request->request->get('reply-content');

    if ($content) {
        $reply = new Reply();
        $user = $security->getUser();
        $reply->setUser($user);
        $reply->setReplyContent($content);
        $reply->setThread($thread);
        $reply->setCreatedAt(new \DateTimeImmutable());

        $entityManager->persist($reply);
        $entityManager->flush();
    }

    return $this->redirectToRoute('app_thread_index', ['id' => $thread->getForum()->getId()]);
}

 
   

    #[Route('/user/reply/edit/{id}', name: 'app_reply_edit', methods: ['POST'])]
    public function editr(Request $request, ReplyRepository $replyRepository, Reply $reply,EntityManagerInterface $entityManager): Response
    {
        if (!$reply) {
            return new JsonResponse(['error' => 'Reply not found'], Response::HTTP_NOT_FOUND);
        }
    
        // Get the submitted form data
        $content = $request->request->get('content');
    
        if (empty(trim($content))) {
            return new JsonResponse(['error' => 'Content cannot be empty'], Response::HTTP_BAD_REQUEST);
        }
    
        $reply->setreplyContent($content);
        $entityManager->flush();
    
        return $this->redirectToRoute('app_thread_index', ['id' => $reply->getThread()->getForum()->getId()]);
    }
    
    

    #[Route('/user/{id}/deleter', name: 'app_reply_delete', methods: ['POST'])]
    public function deletereply(Request $request, Reply $reply, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $csrfToken = $request->request->get('_token');
        $logger->info('CSRF Token: ' . $csrfToken);
        $logger->info('Expected CSRF Token: ' . 'delete' . $reply->getId());
    
      
    
        $entityManager->remove($reply);
        $entityManager->flush();
    
        $logger->info('Reply deleted successfully.');
    
        return $this->redirectToRoute('app_thread_index', ['id' => $reply->getThread()->getForum()->getId()]);
    }

    #[Route('/user/{id}/delete', name: 'app_thread_delete', methods: ['POST'])]
    public function deletet(Request $request, Thread $thread, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $thread->getId(), $request->request->get('_token'))) {
            $entityManager->remove($thread);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_thread_index', ['id' => $thread->getForum()->getId()]);
    }

}
