<?php

namespace App\Entity;

use App\Repository\ApplicationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationRepository::class)]
class Application
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $application_date = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column(length: 255)]
    private ?string $cover_letter = null;

    #[ORM\Column(length: 255)]
    private ?string $cv_path = null;

    #[ORM\ManyToOne(targetEntity: Offer::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(name: 'id_offer', referencedColumnName: 'id')]
    private ?Offer $offer = null;

    #[ORM\ManyToOne(targetEntity: Student::class, inversedBy: 'applications')]
    #[ORM\JoinColumn(name: 'id_student', referencedColumnName: 'id')]
    private ?Student $student = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplicationDate(): ?\DateTimeInterface
    {
        return $this->application_date;
    }

    public function setApplicationDate(\DateTimeInterface $application_date): static
    {
        $this->application_date = $application_date;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getCoverLetter(): ?string
    {
        return $this->cover_letter;
    }

    public function setCoverLetter(string $cover_letter): static
    {
        $this->cover_letter = $cover_letter;

        return $this;
    }

    public function getCvPath(): ?string
    {
        return $this->cv_path;
    }

    public function setCvPath(string $cv_path): static
    {
        $this->cv_path = $cv_path;

        return $this;
    }

    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    public function setOffer(?Offer $offer): static
    {
        $this->offer = $offer;

        return $this;
    }

    public function getStudent(): ?Student
    {
        return $this->student;
    }

    public function setStudent(?Student $student): static
    {
        $this->student = $student;

        return $this;
    }
}
