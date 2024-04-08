<?php

namespace App\Entity;

use App\Repository\RangRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter; // Permet de faire des recherche 
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;  // Permet de filter par ordre alphabétique
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(paginationItemsPerPage: 20, 
operations:[new Get(normalizationContext:['groups' => 'rang:item']),
            new GetCollection(normalizationContext:['groups' => 'rang:list'])])]
#[ApiFilter(OrderFilter::class, properties:['scoreMax' => 'ASC'])] 

#[ORM\Entity(repositoryClass: RangRepository::class)]
class Rang
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['rang:list','rang:item','joueur:list','joueur:item'])]
    private ?int $id = null;

    #[Groups(['rang:list','rang:item','joueur:list','joueur:item'])]
    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\Column]
    #[Groups(['rang:list','rang:item'])]
    private ?int $scoreMin = null;

    #[ORM\Column]
    #[Groups(['rang:list','rang:item'])]
    private ?int $scoreMax = null;

    #[Groups(['rang:list','rang:item','joueur:list','joueur:item'])]
    #[ORM\OneToMany(mappedBy: 'rang', targetEntity: Joueur::class)]
    private Collection $joueurs;

    public function __construct()
    {
        $this->joueurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): static
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getScoreMin(): ?int
    {
        return $this->scoreMin;
    }

    public function setScoreMin(int $scoreMin): static
    {
        $this->scoreMin = $scoreMin;

        return $this;
    }

    public function getScoreMax(): ?int
    {
        return $this->scoreMax;
    }

    public function setScoreMax(int $scoreMax): static
    {
        $this->scoreMax = $scoreMax;

        return $this;
    }

    /**
     * @return Collection<int, Joueur>
     */
    public function getJoueurs(): Collection
    {
        return $this->joueurs;
    }

    public function addJoueur(Joueur $joueur): static
    {
        if (!$this->joueurs->contains($joueur)) {
            $this->joueurs->add($joueur);
            $joueur->setRang($this);
        }

        return $this;
    }

    public function removeJoueur(Joueur $joueur): static
    {
        if ($this->joueurs->removeElement($joueur)) {
            // set the owning side to null (unless already changed)
            if ($joueur->getRang() === $this) {
                $joueur->setRang(null);
            }
        }

        return $this;
    }
}
