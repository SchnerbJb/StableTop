<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
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
    #[ORM\OneToMany(targetEntity: JDS::class, mappedBy: 'categorie')]
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
            $jD->setCategorie($this);
        }

        return $this;
    }

    public function removeJD(JDS $jD): static
    {
        if ($this->jDS->removeElement($jD)) {
            // set the owning side to null (unless already changed)
            if ($jD->getCategorie() === $this) {
                $jD->setCategorie(null);
            }
        }

        return $this;
    }
}
