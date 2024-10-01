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
     * @var Collection<int, ExternalHourRecord>
     */
    #[ORM\OneToMany(targetEntity: ExternalHourRecord::class, mappedBy: 'year', orphanRemoval: true)]
    private Collection $externalHourRecords;

    #[ORM\Column(length: 9)]
    private ?string $name = null;

    /**
     * @var Collection<int, Semester>
     */
    #[ORM\OneToMany(targetEntity: Semester::class, mappedBy: 'year', orphanRemoval: true)]
    private Collection $semesters;

    public function __construct()
    {
        $this->externalHourRecords = new ArrayCollection();
        $this->semesters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $semester->setYear($this);
        }

        return $this;
    }

    public function removeSemester(Semester $semester): static
    {
        if ($this->semesters->removeElement($semester)) {
            // set the owning side to null (unless already changed)
            if ($semester->getYear() === $this) {
                $semester->setYear(null);
            }
        }

        return $this;
    }
}
