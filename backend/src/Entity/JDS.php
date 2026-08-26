<?php

namespace App\Entity;

use App\Repository\JDSRepository;
use App\Enum\DureeJDS;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: JDSRepository::class)]
class JDS
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['jds:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['jds:read'])]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Groups(['jds:read'])]
    private ?string $editeur = null;

    #[ORM\Column]
    #[Groups(['jds:read'])]
    private ?int $ageMin = null;

    #[ORM\Column]
    #[Groups(['jds:read'])]
    private ?int $nbJoueurMin = null;

    #[ORM\Column]
    #[Groups(['jds:read'])]
    private ?int $nbJoueurMax = null;

    #[ORM\Column]
    #[Groups(['jds:read'])]
    private ?bool $solo = null;

    #[ORM\Column]
    #[Groups(['jds:read'])]
    private ?bool $cooperatif = null;

    /**
     * @var Collection<int, Mecanique>
     */
    #[ORM\ManyToMany(targetEntity: Mecanique::class, inversedBy: 'jDS')]
    #[Groups(['jds:read'])]
    private Collection $mecanique;

    #[ORM\ManyToOne(inversedBy: 'jDS')]
    #[Groups(['jds:read'])]
    private ?Categorie $categorie = null;

    #[ORM\Column(enumType: DureeJDS::class, nullable: true)]
    #[Groups(['jds:read'])]
    private ?DureeJDS $duree = null;

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

    public function setAgemin(int $ageMin): static
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

    public function isCooperatif(): ?bool
    {
        return $this->cooperatif;
    }

    public function setCooperatif(bool $cooperatif): static
    {
        $this->cooperatif = $cooperatif;

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

    public function getDuree(): ?DureeJDS
    {
        return $this->duree;
    }

    public function setDuree(?DureeJDS $duree): static
    {
        $this->duree = $duree;

        return $this;
    }
}
