<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use App\Repository\ReponseRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La réponse ne peut pas être vide.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La réponse ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $rep = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "La date est obligatoire.")]
    #[Assert\Type(type: \DateTimeInterface::class, message: "Veuillez entrer une date valide.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToOne(mappedBy: 'id_rep', cascade: ['persist', 'remove'])]
    private ?Reclamation $reclamation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRep(): ?string
    {
        return $this->rep;
    }

    public function setRep(?string $rep): static
    {
        $this->rep = $rep;
        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;
        return $this;
    }

    public function getReclamation(): ?Reclamation
    {
        return $this->reclamation;
    }

    public function setReclamation(?Reclamation $reclamation): static
    {
        if ($reclamation === null && $this->reclamation !== null) {
            $this->reclamation->setIdRep(null);
        }

        if ($reclamation !== null && $reclamation->getIdRep() !== $this) {
            $reclamation->setIdRep($this);
        }

        $this->reclamation = $reclamation;

        return $this;
    }
}
