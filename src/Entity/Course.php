<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CourseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CourseRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Delete(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['course_read']],
    denormalizationContext: ['groups' => ['course_write']],
    order: ['courseTitle' => 'ASC'],
)]
class Course
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['course_read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(cascade: ['remove'], inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['course_read', 'course_write', 'affectation_read'])]
    private ?CourseTitle $courseTitle = null;

    #[ORM\ManyToOne(inversedBy: 'courses')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['course_read', 'course_write', 'courseTitle_info'])]
    private ?TypeCourse $typeCourse = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Groups(['course_read', 'course_write'])]
    private ?string $SAESupport = null;

    #[ORM\Column]
    #[Assert\GreaterThan(0)]
    #[Groups(['course_read', 'course_write'])]
    private ?int $groupMaxNumber = null;

    /**
     * @var Collection<int, Affectation>
     */
    #[ORM\OneToMany(targetEntity: Affectation::class, mappedBy: 'course', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Groups(['course_read', 'course_write'])]
    private Collection $affectations;

    /**
     * @var Collection<int, HourlyVolume>
     */
    #[ORM\OneToMany(targetEntity: HourlyVolume::class, mappedBy: 'course', orphanRemoval: true)]
    #[Groups(['course_read', 'affectation_read_graph', 'courseTitle_info'])]
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

    public function getCourseTitle(): ?CourseTitle
    {
        return $this->courseTitle;
    }

    public function setCourseTitle(?CourseTitle $courseTitle): static
    {
        $this->courseTitle = $courseTitle;
        $courseTitle->addCourse($this);

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

    public function getVolume(): float
    {
        $volume = 0;
        foreach ($this->hourlyVolumes as $hourlyVolume) {
            $volume += $hourlyVolume->getVolume();
        }

        return $volume;
    }

    public function getAffectedHours(): float
    {
        $volume = 0;
        foreach ($this->affectations as $affectation) {
            $groups = $affectation->getNumberGroupTaken();
            foreach ($this->hourlyVolumes as $hourlyVolume) {
                $volume += $hourlyVolume->getVolume() * $groups;
            }
        }

        return $volume;
    }

    public function getAffectedGroups(): int
    {
        $groups = 0;
        foreach ($this->affectations as $affectation) {
            $groups += $affectation->getNumberGroupTaken();
        }

        return $groups;
    }
}
