<?php

namespace App\Entity;

use App\Repository\PartieRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter; // Permet de faire des recherche 
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;  // Permet de filter par ordre alphabétique
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(paginationItemsPerPage: 20, 
operations:[new Get(normalizationContext:['groups' => 'partie:item']),
            new Post(normalizationContext: ['groups' => 'partie:write']),
            new GetCollection(normalizationContext:['groups' => 'partie:list'])])]
#[ORM\Entity(repositoryClass: PartieRepository::class)]
class Partie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['partie:list','partie:item','partie:write','joueur:item','joueur:list'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateDebut = null;

    #[Groups(['partie:list','partie:item','partie:write','joueur:item','joueur:list'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateFin = null;

    #[Groups(['partie:list','partie:item','partie:write'])]
    #[ORM\Column(nullable: true)]
    private ?bool $JoueurGagne = null;

    #[ORM\Column]
    #[Groups(['partie:list','partie:item','partie:write','joueur:item','joueur:list'])]
    private ?int $pointJoueur = null;

    #[ORM\Column]
    #[Groups(['partie:list','partie:item','partie:write','joueur:item','joueur:list'])]
    private ?int $pointBanquier = null;

    #[ORM\ManyToOne(inversedBy: 'parties',)]
    #[Groups(['partie:list','partie:item','partie:write','joueur:item','joueur:list'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Joueur $joueur = null;

    #[ORM\Column(length: 25)]
    #[Groups(['partie:list','partie:item','partie:write','joueur:item','joueur:list'])]
    private ?string $resultat = null;

    #[ORM\Column(length: 5)]
    #[Groups(['partie:list','partie:item','partie:write','joueur:item','joueur:list'])]
    private ?string $jetons = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function isJoueurGagne(): ?bool
    {
        return $this->JoueurGagne;
    }

    public function setJoueurGagne(bool $estGagne): static
    {
        $this->JoueurGagne = $estGagne;

        return $this;
    }

    public function getPointJoueur(): ?int
    {
        return $this->pointJoueur;
    }

    public function setPointJoueur(int $pointJoueur): static
    {
        $this->pointJoueur = $pointJoueur;

        return $this;
    }

    public function getPointBanquier(): ?int
    {
        return $this->pointBanquier;
    }

    public function setPointBanquier(int $pointBanquier): static
    {
        $this->pointBanquier = $pointBanquier;

        return $this;
    }

    public function getJoueur(): ?Joueur
    {
        return $this->joueur;
    }

    public function setJoueur(?Joueur $joueur): static
    {
        $this->joueur = $joueur;

        return $this;
    }

    public function getResultat(): ?string
    {
        return $this->resultat;
    }

    public function setResultat(string $resultat): static
    {
        $this->resultat = $resultat;

        return $this;
    }

    public function getJetons(): ?string
    {
        return $this->jetons;
    }

    public function setJetons(string $jetons): static
    {
        $this->jetons = $jetons;

        return $this;
    }
}
