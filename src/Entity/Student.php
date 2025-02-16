<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
        $this->applications = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    // ATTRIBUTS NECESSAIRES A OFFRE.

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'student')]
    private Collection $applications;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'student')]
    private Collection $reservations;

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
            $application->setStudent($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getStudent() === $this) {
                $application->setStudent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setStudent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getStudent() === $this) {
                $reservation->setStudent(null);
            }
        }

        return $this;
    }
}
