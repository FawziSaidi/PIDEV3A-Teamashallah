<?php

namespace App\Entity;

use App\Repository\HRMStageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HRMStageRepository::class)]
class HRMStage extends User
{
    #[ORM\Column(length: 255)]
    private ?string $company = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): static
    {
        $this->company = $company;

        return $this;
    }

    public function __construct()
    {
        parent::__construct();
        $this->setRoles(['ROLE_HRM_STAGE']);
    }
}
