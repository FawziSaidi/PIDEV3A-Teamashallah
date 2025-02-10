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

#[Route('/forum')]
final class ForumController extends AbstractController
{
    #[Route('/', name: 'forum_index', methods: ['GET', 'POST' ])]
    public function index(Request $request, ForumRepository $forumRepository, EntityManagerInterface $entityManager): Response
    {
        $forums = $forumRepository->findAll();

        $forum = new Forum();
        $createForm = $this->createForm(ForumFormType::class, $forum);


        // Handle editing (if a forum is being updated)
        $editForm = $this->createForm(ForumFormType::class); 

        return $this->render('forum/index.html.twig', [
            'forums' => $forums,
            'createForm' => $createForm->createView(),
            'editForm' => $editForm->createView(),
        ]);
    
    }

    #[Route('/create', name: 'forum_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $forum = new Forum();
        $form = $this->createForm(ForumFormType::class, $forum);

        $form->handleRequest($request);
if ($form->isSubmitted() && $form->isValid()) { 
            $forum->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($forum);
            $entityManager->flush();

            return $this->json(['status' => 'success']);
        }else {
            $errors = [];
            foreach ($form->getErrors(true) as $error) {
                $errors[] = $error->getMessage();
            }
            return $this->json(['status' => 'error', 'message' => $errors]);
        }

        return $this->json(['status' => 'error']);
    }
    
  
    
    #[Route('/edit/{id}', name: 'forum_edit', methods: ['POST'])]
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
    #[Route('/{id}', name: 'forum_show', methods: ['GET'])]
    public function show(Forum $forum): Response
    {
        return $this->render('forum/show.html.twig', [
            'forum' => $forum,
        ]);
    }

    #[Route('/{id}', name: 'forum_delete', methods: ['POST'])]
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
}
