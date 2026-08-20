<?php

namespace App\Entity;

use App\Repository\MecaniqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MecaniqueRepository::class)]
class Mecanique
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    /**
     * @var Collection<int, JDS>
     */
    #[ORM\ManyToMany(targetEntity: JDS::class, mappedBy: 'Mecanique')]
    private Collection $jDS;

    public function __construct()
    {
        $this->jDS = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return Collection<int, JDS>
     */
    public function getJDS(): Collection
    {
        return $this->jDS;
    }

    public function addJD(JDS $jD): static
    {
        if (!$this->jDS->contains($jD)) {
            $this->jDS->add($jD);
            $jD->addMecanique($this);
        }

        return $this;
    }

    public function removeJD(JDS $jD): static
    {
        if ($this->jDS->removeElement($jD)) {
            $jD->removeMecanique($this);
        }

        return $this;
    }
}
