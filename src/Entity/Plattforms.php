<?php

namespace App\Entity;

use App\Repository\PlattformsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlattformsRepository::class)]
class Plattforms
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $name;

    #[ORM\ManyToMany(targetEntity: Games::class, mappedBy: 'plattform')]
    private $games;

    public function __construct()
    {
        $this->games = new ArrayCollection();
    }

	public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|Games[]
     */
    public function getGames(): Collection
    {
        return $this->games;
    }

    public function addGame(Games $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->addPlattform($this);
        }

        return $this;
    }

    public function removeGame(Games $game): self
    {
        if ($this->games->removeElement($game)) {
            $game->removePlattform($this);
        }

        return $this;
    }
}
