<?php

namespace App\Entity;

use App\Repository\JoueurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter; // Permet de faire des recherche 
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;  // Permet de filter par ordre alphabétique
use Symfony\Component\Serializer\Annotation\Groups;
use App\State\UserStateProcessor;

#[ApiResource(paginationItemsPerPage: 20, 
operations:[new Get(normalizationContext:['groups' => 'joueur:item']),
            new GetCollection(normalizationContext:['groups' => 'joueur:list']),
            new Post(processor: UserStateProcessor::class,
                     normalizationContext: ['groups' => 'joueur:write']),
            new Patch(security: "is_granted('ROLE_ADMIN') or object == user"),
            ])]
#[ApiFilter(OrderFilter::class, properties:['pointElo' => 'DESC'])] 
#[ApiFilter(SearchFilter::class, properties:['email' => 'exact'])]
#[ORM\Entity(repositoryClass: JoueurRepository::class)]

class Joueur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['joueur:list','joueur:item','joueur:write','partie:item','partie:list'])]
    private ?int $id = null;

    #[Groups(['joueur:list','joueur:item','joueur:write'])]
    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[Groups(['joueur:list','joueur:item','partie:list','partie:item','joueur:write'])]
    #[ORM\Column(length: 50)]
    private ?string $pseudonyme = null;

    #[Groups(['joueur:list','joueur:item','partie:list','partie:item','joueur:write'])]
    #[ORM\Column]
    private ?int $nbrWins = 0;

    #[Groups(['joueur:list','joueur:item','partie:list','partie:item','joueur:write'])]
    #[ORM\Column]
    private ?int $totalParties = 0;

    #[Groups(['joueur:list','joueur:item','partie:list','partie:item','joueur:write'])]
    #[ORM\Column]
    private ?int $pointElo = 0;

    #[Groups(['joueur:list','joueur:item','joueur:write','joueur:write'])]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateInscription = null;

    #[Groups(['joueur:list','joueur:item','rang:item','rang:list'])]
    #[ORM\ManyToOne(inversedBy: 'joueurs')]
    #[ORM\JoinColumn(nullable: true)]   
    private ?Rang $rang = null;

    #[Groups(['joueur:list','joueur:item','partie:item','partie:list'])]
    #[ORM\OneToMany(mappedBy: 'joueur', targetEntity: Partie::class)]
    private Collection $parties;

    public function __construct()
    {
        $this->parties = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudonyme(): ?string
    {
        return $this->pseudonyme;
    }

    public function setPseudonyme(string $pseudonyme): static
    {
        $this->pseudonyme = $pseudonyme;

        return $this;
    }

    public function getNbrWins(): ?int
    {
        return $this->nbrWins;
    }

    public function setNbrWins(int $nbrWins): static
    {
        $this->nbrWins = $nbrWins;

        return $this;
    }

    public function getTotalParties(): ?int
    {
        return $this->totalParties;
    }

    public function setTotalParties(int $totalParties): static
    {
        $this->totalParties = $totalParties;

        return $this;
    }

    public function getPointElo(): ?int
    {
        return $this->pointElo;
    }

    public function setPointElo(int $pointElo): static
    {
        $this->pointElo = $pointElo;

        return $this;
    }

    public function getDateInscription(): ?\DateTimeInterface
    {
        return $this->dateInscription;
    }

    public function setDateInscription(\DateTimeInterface $dateInscription): static
    {
        $this->dateInscription = $dateInscription;

        return $this;
    }

    public function getRang(): ?Rang
    {
        return $this->rang;
    }

    public function setRang(?Rang $rang): static
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * @return Collection<int, Partie>
     */
    public function getParties(): Collection
    {
        return $this->parties;
    }

    public function addParty(Partie $party): static
    {
        if (!$this->parties->contains($party)) {
            $this->parties->add($party);
            $party->setJoueur($this);
        }

        return $this;
    }

    public function removeParty(Partie $party): static
    {
        if ($this->parties->removeElement($party)) {
            // set the owning side to null (unless already changed)
            if ($party->getJoueur() === $this) {
                $party->setJoueur(null);
            }
        }

        return $this;
    }
}
