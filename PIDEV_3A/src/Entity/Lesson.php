<?php

namespace App\Entity;

use App\Repository\LessonRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Course;

#[ORM\Entity(repositoryClass: LessonRepository::class)]
#[ORM\Table(name: 'lesson')]
class Lesson
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Title cannot be empty')]
    #[Assert\Length(max: 255, maxMessage: 'Title cannot exceed 255 characters')]
    private string $title;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '1024k',
        mimeTypes: ['image/jpeg', 'image/png'],
        mimeTypesMessage: 'Please upload a valid image file (JPG or PNG)'
    )]
    private ?string $thumbnail = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url(message: 'Please enter a valid URL')]
    private ?string $videoUrl = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '50M',
        mimeTypes: ['application/pdf'],
        mimeTypesMessage: 'Please upload a valid PDF file'
    )]
    private ?string $pdf = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(
        maxSize: '50M',
        mimeTypes: [
            'application/vnd.ms-powerpoint',
            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
        ],
        mimeTypesMessage: 'Please upload a valid PPT file'
    )]
    private ?string $ppt = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    #[Assert\Positive(message: 'Duration must be a positive number')]
    private ?int $duration = null;

    #[ORM\ManyToOne(targetEntity: Course::class, inversedBy: 'lessons')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Course cannot be empty')]
    private ?Course $course = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Name cannot be empty')]
    private ?string $name = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $content = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\Url(message: 'Please enter a valid URL')]
    private ?string $quizUrl = null;

    #[ORM\Column(type: 'boolean')]
    private bool $hasQuizzes = false;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;
        return $this;
    }

    public function getThumbnail(): ?string
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?string $thumbnail): self
    {
        $this->thumbnail = $thumbnail;
        return $this;
    }

    public function getVideoUrl(): ?string
    {
        return $this->videoUrl;
    }

    public function setVideoUrl(?string $videoUrl): self
    {
        $this->videoUrl = $videoUrl;
        return $this;
    }

    public function getPdf(): ?string
    {
        return $this->pdf;
    }

    public function setPdf(?string $pdf): self
    {
        $this->pdf = $pdf;
        return $this;
    }

    public function getPpt(): ?string
    {
        return $this->ppt;
    }

    public function setPpt(?string $ppt): self
    {
        $this->ppt = $ppt;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;
        return $this;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): self
    {
        $this->course = $course;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;
        return $this;
    }

    public function getQuizUrl(): ?string
    {
        return $this->quizUrl;
    }

    public function setQuizUrl(?string $quizUrl): self
    {
        $this->quizUrl = $quizUrl;
        return $this;
    }

    public function getHasQuizzes(): bool
    {
        return $this->hasQuizzes;
    }

    public function setHasQuizzes(bool $hasQuizzes): self
    {
        $this->hasQuizzes = $hasQuizzes;
        return $this;
    }
}