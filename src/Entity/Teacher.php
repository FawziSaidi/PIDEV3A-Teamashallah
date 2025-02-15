<?php

namespace App\Entity;

use App\Repository\TeacherRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TeacherRepository::class)]
class Teacher extends User
{
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $specialization = null;

    #[ORM\Column(nullable: true)]
    private ?array $courses_taught = null;

    #[ORM\Column]
    private ?int $years_of_experience = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpecialization(): ?string
    {
        return $this->specialization;
    }

    public function setSpecialization(?string $specialization): static
    {
        $this->specialization = $specialization;

        return $this;
    }

    public function getCoursesTaught(): ?array
    {
        return $this->courses_taught;
    }

    public function setCoursesTaught(?array $courses_taught): static
    {
        $this->courses_taught = $courses_taught;

        return $this;
    }

    public function getYearsOfExperience(): ?int
    {
        return $this->years_of_experience;
    }

    public function setYearsOfExperience(int $years_of_experience): static
    {
        $this->years_of_experience = $years_of_experience;

        return $this;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_TEACHER']);
    }
}
