<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Semester $semester = null;

    /**
     * @var Collection<int, CourseTitle>
     */
    #[ORM\OneToMany(targetEntity: CourseTitle::class, mappedBy: 'module', orphanRemoval: true)]
    private Collection $courseTitles;

    public function __construct()
    {
        $this->courseTitles = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSemester(): ?Semester
    {
        return $this->semester;
    }

    public function setSemester(?Semester $semester): static
    {
        $this->semester = $semester;

        return $this;
    }

    /**
     * @return Collection<int, CourseTitle>
     */
    public function getCourseTitles(): Collection
    {
        return $this->courseTitles;
    }

    public function addClassTitle(CourseTitle $classTitle): static
    {
        if (!$this->courseTitles->contains($classTitle)) {
            $this->courseTitles->add($classTitle);
            $classTitle->setModule($this);
        }

        return $this;
    }

    public function removeClassTitle(CourseTitle $classTitle): static
    {
        if ($this->courseTitles->removeElement($classTitle)) {
            // set the owning side to null (unless already changed)
            if ($classTitle->getModule() === $this) {
                $classTitle->setModule(null);
            }
        }

        return $this;
    }
}
