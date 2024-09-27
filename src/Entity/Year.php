<?php

namespace App\Entity;

use App\Repository\YearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: YearRepository::class)]
class Year
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'year', orphanRemoval: true)]
    private Collection $courses;

    /**
     * @var Collection<int, ExternalHourRecord>
     */
    #[ORM\OneToMany(targetEntity: ExternalHourRecord::class, mappedBy: 'year', orphanRemoval: true)]
    private Collection $externalHourRecords;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->externalHourRecords = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Course>
     */
    public function getCourses(): Collection
    {
        return $this->courses;
    }

    public function addCourse(Course $course): static
    {
        if (!$this->courses->contains($course)) {
            $this->courses->add($course);
            $course->setYear($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getYear() === $this) {
                $course->setYear(null);
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
            $externalHourRecord->setYear($this);
        }

        return $this;
    }

    public function removeExternalHourRecord(ExternalHourRecord $externalHourRecord): static
    {
        if ($this->externalHourRecords->removeElement($externalHourRecord)) {
            // set the owning side to null (unless already changed)
            if ($externalHourRecord->getYear() === $this) {
                $externalHourRecord->setYear(null);
            }
        }

        return $this;
    }
}
