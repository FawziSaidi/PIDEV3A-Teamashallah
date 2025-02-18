<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]
class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "La réclamation ne peut pas être vide.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "La réclamation ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $rec = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message: "La date ne peut pas être vide.")]
    #[Assert\Type(type: \DateTimeInterface::class, message: "La date doit être valide.")]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "L'émetteur est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le nom de l'émetteur ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $issuer = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le motif est obligatoire.")]
    #[Assert\Length(
        max: 255,
        maxMessage: "Le motif ne peut pas dépasser {{ limit }} caractères."
    )]
    private ?string $motif = null;

    #[ORM\OneToOne(inversedBy: 'reclamation', cascade: ['persist', 'remove'])]
    private ?Reponse $id_rep = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRec(): ?string
    {
        return $this->rec;
    }

    public function setRec(?string $rec): static
    {
        $this->rec = $rec;
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

    public function getIssuer(): ?string
    {
        return $this->issuer;
    }

    public function setIssuer(?string $issuer): static
    {
        $this->issuer = $issuer;
        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(?string $motif): static
    {
        $this->motif = $motif;
        return $this;
    }

    public function getIdRep(): ?Reponse
    {
        return $this->id_rep;
    }

    public function setIdRep(?Reponse $id_rep): static
    {
        $this->id_rep = $id_rep;
        return $this;
    }
}
