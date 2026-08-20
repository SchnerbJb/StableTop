<?php

namespace App\Entity;

use App\Repository\JDSRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: JDSRepository::class)]
class JDS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $editeur = null;

    #[ORM\Column]
    private ?int $ageMin = null;

    #[ORM\Column]
    private ?int $nbJoueurMin = null;

    #[ORM\Column]
    private ?int $nbJoueurMax = null;

    #[ORM\Column]
    private ?bool $solo = null;

    #[ORM\Column]
    private ?bool $coopératif = null;

    /**
     * @var Collection<int, Mecanique>
     */
    #[ORM\ManyToMany(targetEntity: Mecanique::class, inversedBy: 'jDS')]
    private Collection $mecanique;

    #[ORM\ManyToOne(inversedBy: 'jDS')]
    private ?Categorie $categorie = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $durée = null;

    public function __construct()
    {
        $this->mecanique = new ArrayCollection();
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

    public function getEditeur(): ?string
    {
        return $this->editeur;
    }

    public function setEditeur(string $editeur): static
    {
        $this->editeur = $editeur;

        return $this;
    }
    
   public function getageMin(): ?int
    {
        return $this->ageMin;
    }

    public function setageMin(int $ageMin): static
    {
        $this->ageMin = $ageMin;

        return $this;
    }

    public function getNbJoueurMin(): ?int
    {
        return $this->nbJoueurMin;
    }

    public function setNbJoueurMin(int $nbJoueurMin): static
    {
        $this->nbJoueurMin = $nbJoueurMin;

        return $this;
    }

    public function getNbJoueurMax(): ?int
    {
        return $this->nbJoueurMax;
    }

    public function setNbJoueurMax(int $nbJoueurMax): static
    {
        $this->nbJoueurMax = $nbJoueurMax;

        return $this;
    }

    public function isSolo(): ?bool
    {
        return $this->solo;
    }

    public function setSolo(bool $solo): static
    {
        $this->solo = $solo;

        return $this;
    }

    public function isCoopératif(): ?bool
    {
        return $this->coopératif;
    }

    public function setCoopératif(bool $coopératif): static
    {
        $this->coopératif = $coopératif;

        return $this;
    }

    /**
     * @return Collection<int, Mecanique>
     */
    public function getMecanique(): Collection
    {
        return $this->mecanique;
    }

    public function addMecanique(Mecanique $mecanique): static
    {
        if (!$this->mecanique->contains($mecanique)) {
            $this->mecanique->add($mecanique);
        }

        return $this;
    }

    public function removeMecanique(Mecanique $mecanique): static
    {
        $this->mecanique->removeElement($mecanique);

        return $this;
    }

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;

        return $this;
    }

    public function getDurée(): ?string
    {
        return $this->durée;
    }

    public function setDurée(?string $durée): static
    {
        $this->durée = $durée;

        return $this;
    }
}
