<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\WeekRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: WeekRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Delete(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['week_read']],
    denormalizationContext: ['groups' => ['week_write']],
    order: ['number' => 'ASC'],
)]
class Week
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['week_read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['week_read', 'course_read', 'affectation_read_graph'])]
    private ?int $number = null;

    #[ORM\ManyToOne(targetEntity: Semester::class, inversedBy: 'weeks')]
    #[Groups(['course_read', 'affectation_read_graph'])]
    private Semester $semesters;

    /**
     * @var Collection<int, HourlyVolume>
     */
    #[ORM\OneToMany(targetEntity: HourlyVolume::class, mappedBy: 'week', orphanRemoval: true)]
    private Collection $hourlyVolumes;

    public function __construct()
    {
        $this->hourlyVolumes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): static
    {
        $this->number = $number;

        return $this;
    }

    public function getSemesters(): Semester
    {
        return $this->semesters;
    }

    public function setSemesters(Semester $semesters): void
    {
        $this->semesters = $semesters;
    }

    /**
     * @return Collection<int, HourlyVolume>
     */
    public function getHourlyVolumes(): Collection
    {
        return $this->hourlyVolumes;
    }

    public function addHourlyVolume(HourlyVolume $hourlyVolume): static
    {
        if (!$this->hourlyVolumes->contains($hourlyVolume)) {
            $this->hourlyVolumes->add($hourlyVolume);
            $hourlyVolume->setWeek($this);
        }

        return $this;
    }

    public function removeHourlyVolume(HourlyVolume $hourlyVolume): static
    {
        if ($this->hourlyVolumes->removeElement($hourlyVolume)) {
            // set the owning side to null (unless already changed)
            if ($hourlyVolume->getWeek() === $this) {
                $hourlyVolume->setWeek(null);
            }
        }

        return $this;
    }
}
