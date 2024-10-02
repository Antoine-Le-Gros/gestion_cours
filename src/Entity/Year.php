<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\YearRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: YearRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Delete(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['year_read']],
    denormalizationContext: ['groups' => ['year_write']],
    order: ['name' => 'ASC'],
)]
class Year
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['year_read'])]
    private ?int $id = null;

    /**
     * @var Collection<int, ExternalHourRecord>
     */
    #[ORM\OneToMany(targetEntity: ExternalHourRecord::class, mappedBy: 'year', orphanRemoval: true)]
    private Collection $externalHourRecords;

    #[ORM\Column(length: 9)]
    #[Groups(['year_read', 'course_read'])]
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
