<?php

namespace App\Entity;

use App\Repository\SemesterRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SemesterRepository::class)]
class Semester
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\Range(min: 1, max: 6)]
    private ?int $number = null;

    /**
     * @var Collection<int, Module>
     */
    #[ORM\OneToMany(targetEntity: Module::class, mappedBy: 'semester', orphanRemoval: true)]
    private Collection $modules;

    #[ORM\ManyToOne(inversedBy: 'semesters')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Year $year = null;

    /**
     * @var Collection<int, Week>
     */
    #[ORM\ManyToMany(targetEntity: Week::class, mappedBy: 'semesters')]
    private Collection $weeks;

    public function __construct()
    {
        $this->modules = new ArrayCollection();
        $this->weeks = new ArrayCollection();
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
     * @return Collection<int, Module>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): static
    {
        if (!$this->modules->contains($module)) {
            $this->modules->add($module);
            $module->setSemester($this);
        }

        return $this;
    }

    public function removeModule(Module $module): static
    {
        if ($this->modules->removeElement($module)) {
            // set the owning side to null (unless already changed)
            if ($module->getSemester() === $this) {
                $module->setSemester(null);
            }
        }

        return $this;
    }

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): static
    {
        $this->year = $year;

        return $this;
    }

    /**
     * @return Collection<int, Week>
     */
    public function getWeeks(): Collection
    {
        return $this->weeks;
    }

    public function addWeek(Week $week): static
    {
        if (!$this->weeks->contains($week)) {
            $this->weeks->add($week);
            $week->addSemester($this);
        }

        return $this;
    }

    public function removeWeek(Week $week): static
    {
        if ($this->weeks->removeElement($week)) {
            $week->removeSemester($this);
        }

        return $this;
    }
}
