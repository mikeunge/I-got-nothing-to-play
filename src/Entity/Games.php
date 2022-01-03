<?php

namespace App\Entity;

use App\Repository\GamesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GamesRepository::class)]
class Games
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $title;

    #[ORM\Column(type: 'text')]
    private $description;

    #[ORM\Column(type: 'text')]
    private $image;

    #[ORM\ManyToMany(targetEntity: Genres::class, inversedBy: 'games')]
    private $genre;

    #[ORM\ManyToOne(targetEntity: Developers::class, inversedBy: 'games')]
    private $developer;

    #[ORM\ManyToMany(targetEntity: Plattforms::class, inversedBy: 'games')]
    private $plattform;

    public function __construct()
    {
        $this->genre = new ArrayCollection();
        $this->plattform = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection|Genres[]
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Genres $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
        }

        return $this;
    }

    public function removeGenre(Genres $genre): self
    {
        $this->genre->removeElement($genre);

        return $this;
    }

    public function getDeveloper(): ?Developers
    {
        return $this->developer;
    }

    public function setDeveloper(?Developers $developer): self
    {
        $this->developer = $developer;

        return $this;
    }

    /**
     * @return Collection|Plattforms[]
     */
    public function getPlattform(): Collection
    {
        return $this->plattform;
    }

    public function addPlattform(Plattforms $plattform): self
    {
        if (!$this->plattform->contains($plattform)) {
            $this->plattform[] = $plattform;
        }

        return $this;
    }

    public function removePlattform(Plattforms $plattform): self
    {
        $this->plattform->removeElement($plattform);

        return $this;
    }
}
