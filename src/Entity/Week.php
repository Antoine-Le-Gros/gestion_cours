<?php

namespace App\Entity;

use App\Repository\WeekRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WeekRepository::class)]
class Week
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $number = null;

    /**
     * @var Collection<int, Semester>
     */
    #[ORM\ManyToMany(targetEntity: Semester::class, inversedBy: 'weeks')]
    private Collection $semesters;

    /**
     * @var Collection<int, HourlyVolume>
     */
    #[ORM\OneToMany(targetEntity: HourlyVolume::class, mappedBy: 'week', orphanRemoval: true)]
    private Collection $hourlyVolumes;

    public function __construct()
    {
        $this->semesters = new ArrayCollection();
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

    /**
     * @return Collection<int, Semester>
     */
    public function getSemesters(): Collection
    {
        return $this->semesters;
    }

    public function addSemester(Semester $semester): static
    {
        if (!$this->semesters->contains($semester)) {
            $this->semesters->add($semester);
        }

        return $this;
    }

    public function removeSemester(Semester $semester): static
    {
        $this->semesters->removeElement($semester);

        return $this;
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
