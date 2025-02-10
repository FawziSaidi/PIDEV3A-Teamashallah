<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StudentRepository::class)]
class Student extends User
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_of_birth = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private ?array $courses_enrolled = null;

    #[ORM\Column(nullable: true)]
    private ?int $year_of_study = null;

    #[ORM\Column(nullable: true)]
    private ?array $certifications = null;

    #[ORM\Column(nullable: true)]
    private ?array $diplomas = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): static
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getCoursesEnrolled(): ?array
    {
        return $this->courses_enrolled;
    }

    public function setCoursesEnrolled(?array $courses_enrolled): static
    {
        $this->courses_enrolled = $courses_enrolled;

        return $this;
    }

    public function getYearOfStudy(): ?int
    {
        return $this->year_of_study;
    }

    public function setYearOfStudy(?int $year_of_study): static
    {
        $this->year_of_study = $year_of_study;

        return $this;
    }

    public function getCertifications(): ?array
    {
        return $this->certifications;
    }

    public function setCertifications(?array $certifications): static
    {
        $this->certifications = $certifications;

        return $this;
    }

    public function getDiplomas(): ?array
    {
        return $this->diplomas;
    }

    public function setDiplomas(?array $diplomas): static
    {
        $this->diplomas = $diplomas;

        return $this;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_STUDENT']);
    }
}
