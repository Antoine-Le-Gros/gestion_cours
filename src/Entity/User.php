<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\UserRepository;
use App\State\MeProvider;
use App\State\UserPasswordHasher;
use App\Validator as CustomAssert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(),
        new Get(),
        new Get(
            uriTemplate: '/me',
            openapiContext: ['summary' => 'Get the current user'],
            normalizationContext: ['groups' => ['user_read']],
            security: "is_granted('ROLE_USER')",
            provider: MeProvider::class,
        ),
        new Patch(
            security: "is_granted('ROLE_USER') and object.getUserIdentifier() == user.getUserIdentifier()",
            processor: UserPasswordHasher::class
        ),
    ],
    normalizationContext: ['groups' => ['user_read']],
    denormalizationContext: ['groups' => ['user_write']],
    order: ['login' => 'ASC'],
)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const ADMINISTRATION = 'administration';
    public const AGGREGATED = 'enseignant agrege';
    public const CERTIFIED = 'enseignant certifie';
    public const RESEARCHER = 'enseignant chercheur';
    public const EXTERNAL = 'vacataire';
    public const TYPE_USER = [
        self::ADMINISTRATION,
        self::AGGREGATED,
        self::CERTIFIED,
        self::RESEARCHER,
        self::EXTERNAL,
    ];
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['user_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    #[Groups(['user_read'])]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Groups(['user_write'])]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course_read', 'user_read', 'user_write', 'affectation_read'])]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course_read', 'user_read', 'user_write', 'affectation_read'])]
    private ?string $lastname = null;

    #[ORM\Column]
    #[Groups(['user_read'])]
    private ?bool $isActive = null;

    #[ORM\Column(length: 50)]
    #[Groups(['user_read', 'user_write'])]
    private ?string $login = null;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'teacher')]
    private Collection $affectations;

    /**
     * @var Collection<int, ExternalHourRecord>
     */
    #[ORM\OneToMany(targetEntity: ExternalHourRecord::class, mappedBy: 'teacher', orphanRemoval: true)]
    #[Groups(['user_read'])]
    private Collection $externalHourRecords;

    #[ORM\Column]
    #[CustomAssert\UserHoursMax]
    #[Groups(['user_read'])]
    private ?int $hoursMax = null;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->externalHourRecords = new ArrayCollection();
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
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
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

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getIsActive(): ?bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @return Collection<int, Affectation>
     */
    public function getAffectations(): Collection
    {
        return $this->affectations;
    }

    public function addAffectation(Affectation $affectation): static
    {
        if (!$this->affectations->contains($affectation)) {
            $this->affectations->add($affectation);
            $affectation->setTeacher($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getTeacher() === $this) {
                $affectation->setTeacher(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ExternalHourRecord>
     */
    public function getExternalHourRecords(): Collection
    {
        return $this->externalHourRecords;
    }

    public function addExternalHourRecord(ExternalHourRecord $externalHourRecord): static
    {
        if (!$this->externalHourRecords->contains($externalHourRecord)) {
            $this->externalHourRecords->add($externalHourRecord);
            $externalHourRecord->setTeacher($this);
        }

        return $this;
    }

    public function removeExternalHourRecord(ExternalHourRecord $externalHourRecord): static
    {
        if ($this->externalHourRecords->removeElement($externalHourRecord)) {
            // set the owning side to null (unless already changed)
            if ($externalHourRecord->getTeacher() === $this) {
                $externalHourRecord->setTeacher(null);
            }
        }

        return $this;
    }

    public function getHoursMax(): ?int
    {
        return $this->hoursMax;
    }

    public function setHoursMax(int $hoursMax): static
    {
        $this->hoursMax = $hoursMax;

        return $this;
    }
}
