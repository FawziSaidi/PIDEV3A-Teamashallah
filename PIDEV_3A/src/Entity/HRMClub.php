<?php

namespace App\Entity;

use App\Repository\HRMClubRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HRMClubRepository::class)]
class HRMClub extends User
{
    #[ORM\Column(nullable: true)]
    private ?array $event = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?array
    {
        return $this->event;
    }

    public function setEvent(?array $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_HRM_CLUB']);
    }
}
