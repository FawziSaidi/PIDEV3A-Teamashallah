<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;
use App\Repository\CourseRepository;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Title cannot be empty')]
    #[Assert\Length(max: 255, maxMessage: 'Title cannot exceed 255 characters')]
    private ?string $title = null;

    #[ORM\Column(type: 'decimal', scale: 2, nullable: true)]
    #[Assert\GreaterThanOrEqual(0)]
    private ?float $price = null;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(message: 'About cannot be empty')]
    private ?string $about = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank(message: 'Date Created cannot be empty')]
    private \DateTimeImmutable $dateCreated;

    #[ORM\Column(type: 'date')]
    #[Assert\GreaterThanOrEqual('today')]
    private ?\DateTimeInterface $startDate = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    private ?\DateTimeImmutable $endDate = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(message: 'Lesson count cannot be empty')]
    #[Assert\Positive(message: 'Lesson count must be at least 1')]
    private int $lessonNumber = 1;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'Teacher name is required')]
    private ?string $teacher = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 255)]
    #[Assert\NotBlank(message: 'University name is required')]
    private ?string $university = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $customUniversity = null;

    #[ORM\Column(type: 'float', nullable: true)]
    private ?float $progress = 0;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $certificat = null;

    #[ORM\Column(type: 'json', nullable: true)]
    #[Assert\NotNull(message: "Requirements cannot be null.")]
    #[Assert\Type(type: "array", message: "Requirements must be an array.")]
    private array $requirements = [];

    #[ORM\Column(type: 'json', nullable: true)]
    private array $preferredPreviousKnowledge = [];

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $applicationProcess = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $fees = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Assert\File(mimeTypes: ['image/jpeg', 'image/png'], mimeTypesMessage: 'Please upload a valid image file (JPG or PNG)')]
    private ?string $thumbnail = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $location = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $category = null;

    #[ORM\Column(type: 'boolean')]
    private ?bool $isPaid = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $personalizedCategory = null;

    #[ORM\OneToMany(mappedBy: 'course', targetEntity: Lesson::class, orphanRemoval: true)]
    private Collection $lessons;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
        $this->dateCreated = new \DateTimeImmutable(); // Automatically set the current date
        $this->lessonNumber = 1; // Default value
        $this->progress = 0;
    }

    // Getters and Setters...

    public function getId(): ?int { return $this->id; }
    public function getName(): ?string { return $this->name; }
    public function setName(string $name): static { $this->name = $name; return $this; }
    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }
    public function getPrice(): ?float { return $this->price; }
    public function setPrice(?float $price): static { $this->price = $price; return $this; }
    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }
    public function getDateCreated(): ?\DateTimeImmutable { return $this->dateCreated; }
    public function setDateCreated(\DateTimeImmutable $dateCreated): static { $this->dateCreated = $dateCreated; return $this; }
    public function getLessonNumber(): ?int { return $this->lessonNumber; }
    public function setLessonNumber(int $lessonNumber): static { $this->lessonNumber = $lessonNumber; return $this; }
    public function getTeacher(): ?string { return $this->teacher; }
    public function setTeacher(string $teacher): static { $this->teacher = $teacher; return $this; }
    public function getUniversity(): ?string { return $this->university; }
    public function setUniversity(string $university): static { $this->university = $university; return $this; }
    public function getProgress(): ?float { return $this->progress; }
    public function setProgress(?float $progress): static { $this->progress = $progress; return $this; }
    public function getCertificat(): ?bool { return $this->certificat; }
    public function setCertificat(?bool $certificat): static { $this->certificat = $certificat; return $this; }
    public function getAbout(): ?string { return $this->about; }
    public function setAbout(?string $about): static { $this->about = $about; return $this; }
    public function getRequirements(): array { return $this->requirements; }
    public function setRequirements(array $requirements): static { $this->requirements = $requirements; return $this; }
    public function getPreferredPreviousKnowledge(): array { return $this->preferredPreviousKnowledge; }
    public function setPreferredPreviousKnowledge(array $preferredPreviousKnowledge): static { $this->preferredPreviousKnowledge = $preferredPreviousKnowledge; return $this; }
    public function getApplicationProcess(): ?string { return $this->applicationProcess; }
    public function setApplicationProcess(?string $applicationProcess): static { $this->applicationProcess = $applicationProcess; return $this; }
    public function getStartDate(): ?\DateTimeInterface { return $this->startDate; }
    public function setStartDate(\DateTimeInterface $startDate): static { $this->startDate = $startDate; return $this; }
    public function getEndDate(): ?\DateTimeImmutable { return $this->endDate; }
    public function setEndDate(?\DateTimeImmutable $endDate): static { $this->endDate = $endDate; return $this; }
    public function getFees(): ?string { return $this->fees; }
    public function setFees(?string $fees): static { $this->fees = $fees; return $this; }
    public function getCategory(): ?string { return $this->category; }
    public function setCategory(?string $category): static { $this->category = $category; return $this; }
    public function getLocation(): ?string { return $this->location; }
    public function setLocation(?string $location): static { $this->location = $location; return $this; }
    public function getThumbnail(): ?string { return $this->thumbnail; }
    public function setThumbnail(?string $thumbnail): static { $this->thumbnail = $thumbnail; return $this; }
    public function getLessons(): Collection { return $this->lessons; }
    public function addLesson(Lesson $lesson): static
    {
        if (!$this->lessons->contains($lesson)) {
            $this->lessons->add($lesson);
        }
        return $this;
    }
    public function removeLesson(Lesson $lesson): static
    {
        if ($this->lessons->removeElement($lesson)) {
            // Set the owning side to null (unless already changed)
            if ($lesson->getCourse() === $this) {
                $lesson->setCourse(null);
            }
        }
        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;
        return $this;
    }

    public function getCustomUniversity(): ?string
    {
        return $this->customUniversity;
    }

    public function setCustomUniversity(?string $customUniversity): static
    {
        $this->customUniversity = $customUniversity;
        return $this;
    }

    public function getIsPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setIsPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;
        return $this;
    }

    public function getPersonalizedCategory(): ?string
    {
        return $this->personalizedCategory;
    }

    public function setPersonalizedCategory(?string $personalizedCategory): static
    {
        $this->personalizedCategory = $personalizedCategory;
        return $this;
    }
}