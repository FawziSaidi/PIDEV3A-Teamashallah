<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Lesson;
use App\Form\CourseType;
use App\Form\LessonType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\CourseRepository;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/course')]
class CourseController extends AbstractController
{
    #[Route('/new', name: 'course_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $course = new Course();
        $form = $this->createForm(CourseType::class, $course);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('university')->getData() === 'Other') {
                $course->setUniversity($form->get('customUniversity')->getData());
            }
            if ($form->get('category')->getData() === 'other') {
                $course->setCategory($form->get('personalizedCategory')->getData());
            }
            $course->setProgress(0); // Set progress to 0 when creating a new course
            $course->setDuration($form->get('duration')->getData()); // Set duration based on calculated value

            // Handle file upload
            $thumbnailFile = $form->get('thumbnail')->getData();
            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$thumbnailFile->guessExtension();

                try {
                    $thumbnailFile->move(
                        $this->getParameter('thumbnails_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $course->setThumbnail($newFilename);
            }

            $entityManager->persist($course);
            $entityManager->flush();

            $this->addFlash('success', 'Course created successfully');

            return $this->redirectToRoute('course_index');
        }

        return $this->render('course/new.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'course_index', methods: ['GET'])]
    public function index(Request $request, CourseRepository $courseRepository): Response
    {
        $sort = $request->query->get('sort', 'title');
        $order = $request->query->get('order', 'asc');
        $search = $request->query->get('search', '');

        $queryBuilder = $courseRepository->createQueryBuilder('c');

        if ($search) {
            $queryBuilder->andWhere('c.title LIKE :search OR c.description LIKE :search OR c.category LIKE :search OR c.university LIKE :search')
                         ->setParameter('search', '%' . $search . '%');
        }

        $queryBuilder->orderBy('c.' . $sort, $order);

        $courses = $queryBuilder->getQuery()->getResult();

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
            'sort' => $sort,
            'order' => $order,
            'search' => $search,
        ]);
    }

    #[Route('/{id}', name: 'course_show', methods: ['GET', 'POST'])]
    public function show(Request $request, CourseRepository $courseRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $course = $courseRepository->find($id);
        if (!$course) {
            throw $this->createNotFoundException('The course does not exist');
        }

        $lesson = new Lesson();
        $lesson->setCourse($course);
        $lessonForm = $this->createForm(LessonType::class, $lesson);

        $lessonForm->handleRequest($request);
        if ($lessonForm->isSubmitted() && $lessonForm->isValid()) {
            $entityManager->persist($lesson);
            $entityManager->flush();

            $this->addFlash('success', 'Lesson created successfully');

            return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
        }

        return $this->render('course/show.html.twig', [
            'course' => $course,
            'lessonForm' => $lessonForm->createView(),
        ]);
    }

    #[Route('/edit/{id}', name: 'course_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CourseRepository $courseRepository, int $id, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $course = $courseRepository->find($id);
        if (!$course) {
            throw $this->createNotFoundException('The course does not exist');
        }

        $form = $this->createForm(CourseType::class, $course, ['is_new' => false]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Debugging: Check if the form is submitted and valid
            dump('Form submitted and valid');

            if ($form->get('university')->getData() === 'Other') {
                $course->setUniversity($form->get('customUniversity')->getData());
            }
            if ($form->get('category')->getData() === 'other') {
                $course->setCategory($form->get('personalizedCategory')->getData());
            }
            $course->setDuration($form->get('duration')->getData());

            // Handle file upload only if a new file is uploaded
            $thumbnailFile = $form->get('thumbnail')->getData();
            if ($thumbnailFile) {
                $originalFilename = pathinfo($thumbnailFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$thumbnailFile->guessExtension();

                try {
                    $thumbnailFile->move(
                        $this->getParameter('thumbnails_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Handle exception if something happens during file upload
                }

                $course->setThumbnail($newFilename);
            }

            // Debugging: Check the course data before saving
            dump($course);

            $entityManager->persist($course); // Force Doctrine to manage the entity
            $entityManager->flush();

            $this->addFlash('success', 'Course updated successfully');

            return $this->redirectToRoute('course_show', ['id' => $course->getId()]);
        } elseif ($form->isSubmitted()) {
            // Debugging: Check if the form is submitted but not valid
            dump('Form submitted but not valid', $form->getErrors(true));
        }

        return $this->render('course/edit.html.twig', [
            'course' => $course,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'course_delete', methods: ['POST'])]
    public function delete(Request $request, CourseRepository $courseRepository, int $id, EntityManagerInterface $entityManager): Response
    {
        $course = $courseRepository->find($id);
        if (!$course) {
            throw $this->createNotFoundException('The course does not exist');
        }

        if ($this->isCsrfTokenValid('delete'.$course->getId(), $request->request->get('_token'))) {
            $entityManager->remove($course);
            $entityManager->flush();

            $this->addFlash('success', 'Course deleted successfully');
        }

        return $this->redirectToRoute('course_index');
    }
}