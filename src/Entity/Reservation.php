<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    #[ORM\JoinColumn(nullable: false, name:'event_id',referencedColumnName:'id')]
    private ?Event $event_id = null;

    #[ORM\Column(length: 255)]
    private ?string $reservation_status = null;

    #[Assert\GreaterThanOrEqual(
        value: 1,
        message: 'Available seats should be greater than or equal to {{ compared_value }}',
    )]
    #[ORM\Column]
    private ?int $number_of_seats = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEventId(): ?event
    {
        return $this->event_id;
    }

    public function setEventId(?event $event_id): static
    {
        $this->event_id = $event_id;

        return $this;
    }

    public function getReservationStatus(): ?string
    {
        return $this->reservation_status;
    }

    public function setReservationStatus(string $reservation_status): static
    {
        $this->reservation_status = $reservation_status;

        return $this;
    }

    public function getNumberOfSeats(): ?int
    {
        return $this->number_of_seats;
    }

    public function setNumberOfSeats(int $number_of_seats): static
    {
        $this->number_of_seats = $number_of_seats;

        return $this;
    }
}
