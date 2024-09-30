<?php

namespace App\Entity;

use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Year $year = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?CourseTitle $courseTitle = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeCourse $typeCourse = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $SAESupport = null;

    #[ORM\Column]
    #[Assert\GreaterThan(0)]
    private ?int $groupMaxNumber = null;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'course')]
    private Collection $affectations;

    /**
     * @var Collection<int, HourlyVolume>
     */
    #[ORM\OneToMany(targetEntity: HourlyVolume::class, mappedBy: 'course', orphanRemoval: true)]
    private Collection $hourlyVolumes;

    public function __construct()
    {
        $this->affectations = new ArrayCollection();
        $this->hourlyVolumes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCourseTitle(): ?CourseTitle
    {
        return $this->courseTitle;
    }

    public function setCourseTitle(?CourseTitle $courseTitle): static
    {
        $this->courseTitle = $courseTitle;

        return $this;
    }

    public function getTypeCourse(): ?TypeCourse
    {
        return $this->typeCourse;
    }

    public function setTypeCourse(?TypeCourse $typeCourse): static
    {
        $this->typeCourse = $typeCourse;

        return $this;
    }

    public function getSAESupport(): ?string
    {
        return $this->SAESupport;
    }

    public function setSAESupport(?string $SAESupport): static
    {
        $this->SAESupport = $SAESupport;

        return $this;
    }

    public function getGroupMaxNumber(): ?int
    {
        return $this->groupMaxNumber;
    }

    public function setGroupMaxNumber(int $groupMaxNumber): static
    {
        $this->groupMaxNumber = $groupMaxNumber;

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
            $affectation->setCourse($this);
        }

        return $this;
    }

    public function removeAffectation(Affectation $affectation): static
    {
        if ($this->affectations->removeElement($affectation)) {
            // set the owning side to null (unless already changed)
            if ($affectation->getCourse() === $this) {
                $affectation->setCourse(null);
            }
        }

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
            $hourlyVolume->setCourse($this);
        }

        return $this;
    }

    public function removeHourlyVolume(HourlyVolume $hourlyVolume): static
    {
        if ($this->hourlyVolumes->removeElement($hourlyVolume)) {
            // set the owning side to null (unless already changed)
            if ($hourlyVolume->getCourse() === $this) {
                $hourlyVolume->setCourse(null);
            }
        }

        return $this;
    }
}
