<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'Title must be at least {{ limit }} characters long',
        maxMessage: 'Title cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Description must be at least {{ limit }} characters long',
        maxMessage: 'Description cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 10,
        max: 255,
        minMessage: 'Details must be at least {{ limit }} characters long',
        maxMessage: 'Details cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(length: 255)]
    private ?string $details = null;

    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: 'Location must be at least {{ limit }} characters long',
        maxMessage: 'Location cannot be longer than {{ limit }} characters',
    )]
    #[ORM\Column(length: 255)]
    private ?string $location = null;

    #[Assert\NotBlank]
    #[Assert\GreaterThanOrEqual(
        value: 10,
        message: 'Available seats should be greater than or equal to {{ compared_value }}',
    )]
    #[ORM\Column]
    private ?int $available_seats = null;

    #[Assert\NotBlank]
    #[Assert\GreaterThan('today',
        message: 'The selected date must be in the future'
    )]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    // #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $poster = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'event_id')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getAvailableSeats(): ?int
    {
        return $this->available_seats;
    }

    public function setAvailableSeats(int $available_seats): static
    {
        $this->available_seats = $available_seats;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(string $poster): static
    {
        $this->poster = $poster;

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
            $reservation->setEventId($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getEventId() === $this) {
                $reservation->setEventId(null);
            }
        }

        return $this;
    }
}
