<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Delete(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['module_read']],
    denormalizationContext: ['groups' => ['module_write']],
    order: ['name' => 'ASC'],
)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['module_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course_read', 'affectation_read', 'module_read', 'module_write'])]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'modules')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Semester $semester = null;

    /**
     * @var Collection<int, CourseTitle>
     */
    #[ORM\ManyToMany(targetEntity: CourseTitle::class, inversedBy: 'modules')]
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

    public function addCourseTitle(CourseTitle $courseTitle): static
    {
        if (!$this->courseTitles->contains($courseTitle)) {
            $this->courseTitles->add($courseTitle);
        }

        return $this;
    }

    public function removeCourseTitle(CourseTitle $courseTitle): static
    {
        $this->courseTitles->removeElement($courseTitle);

        return $this;
    }
}
