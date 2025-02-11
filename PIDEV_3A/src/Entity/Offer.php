<?php

namespace App\Entity;

use App\Repository\OfferRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OfferRepository::class)]
class Offer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Title is required')]
    #[Assert\Length(max: 255, maxMessage: 'Title cannot exceed 255 characters')]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Description is required')]
    #[Assert\Length(max: 255, maxMessage: 'Description cannot exceed 255 characters')]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Duration is required')]
    #[Assert\Length(max: 255, maxMessage: 'Duration cannot exceed 255 characters')]
    private ?string $duration = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $publication_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: 'Expiration date is required')]
    #[Assert\GreaterThan(propertyPath: 'publication_date', message: 'Expiration date must be after the publication date')]
    private ?\DateTimeInterface $expiration_date = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Type is required')]
    // #[Assert\Choice(choices: ['Internship', 'Job'], message: 'Type must be either Internship or Job')]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Desired skills are required')]
    #[Assert\Length(max: 255, maxMessage: 'Desired skills cannot exceed 255 characters')]
    private ?string $desired_skills = null;

    #[ORM\ManyToOne(targetEntity: HRMStage::class, inversedBy: 'offers')]
    #[ORM\JoinColumn(nullable: false, name: 'id_hrm_stage', referencedColumnName: 'id')]
    #[Assert\NotNull(message: 'HRMStage is required')]
    private ?HRMStage $hrmStage = null;

    /**
     * @var Collection<int, Application>
     */
    #[ORM\OneToMany(targetEntity: Application::class, mappedBy: 'offer')]
    private Collection $applications;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->publication_date = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDuration(): ?string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPublicationDate(): ?\DateTimeInterface
    {
        return $this->publication_date;
    }

    public function setPublicationDate(\DateTimeInterface $publication_date): static
    {
        $this->publication_date = $publication_date;

        return $this;
    }

    public function getExpirationDate(): ?\DateTimeInterface
    {
        return $this->expiration_date;
    }

    public function setExpirationDate(\DateTimeInterface $expiration_date): static
    {
        $this->expiration_date = $expiration_date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDesiredSkills(): ?string
    {
        return $this->desired_skills;
    }

    public function setDesiredSkills(string $desired_skills): static
    {
        $this->desired_skills = $desired_skills;

        return $this;
    }

    public function getHrmStage(): ?HRMStage
    {
        return $this->hrmStage;
    }

    public function setHrmStage(?HRMStage $hrmStage): static
    {
        $this->hrmStage = $hrmStage;

        return $this;
    }

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
            $application->setOffer($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): static
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getOffer() === $this) {
                $application->setOffer(null);
            }
        }

        return $this;
    }
}