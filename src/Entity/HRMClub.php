<?php

namespace App\Entity;

use App\Repository\HRMClubRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HRMClubRepository::class)]
class HRMClub extends User
{
    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'hrm_club')]
    private Collection $events;

    #[ORM\Column(length: 255)]
    private ?string $club = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_HRM_CLUB']);
        $this->events = new ArrayCollection();
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setHrmClub($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getHrmClub() === $this) {
                $event->setHrmClub(null);
            }
        }

        return $this;
    }

    public function getClub(): ?string
    {
        return $this->club;
    }

    public function setClub(string $club): static
    {
        $this->club = $club;

        return $this;
    }
}
