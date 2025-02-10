<?php

namespace App\Entity;

use App\Repository\ForumRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message:"title cannot be empty")]
    #[Assert\Length(min : 4 , minMessage:"Title must be at least 4 characters long")]
    private string $titleForum ;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank(message:"description cannot be empty")]
    #[Assert\Length(min : 4 , minMessage:"Description must be at least 4 characters long")]
    private string $descriptionForum;

    #[ORM\Column(length: 255, nullable: false)]
    private string $category ;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitleForum(): ?string
    {
        return $this->titleForum;
    }

    public function setTitleForum(?string $titleForum): static
    {
        $this->titleForum = $titleForum;

        return $this;
    }

    public function getDescriptionForum(): ?string
    {
        return $this->descriptionForum;
    }

    public function setDescriptionForum(?string $descriptionForum): static
    {
        $this->descriptionForum = $descriptionForum;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(?string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
