<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\CourseTitleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CourseTitleRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Delete(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['courseTitle_read']],
    denormalizationContext: ['groups' => ['courseTitle_write']],
    order: ['name' => 'ASC'],
)]
class CourseTitle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['courseTitle_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['course_read', 'courseTitle_read', 'courseTitle_write', 'affectation_read'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Course>
     */
    #[ORM\OneToMany(targetEntity: Course::class, mappedBy: 'classTitle')]
    private Collection $courses;

    /**
     * @var Collection<int, Module>
     */
    #[ORM\ManyToMany(targetEntity: Module::class, mappedBy: 'courseTitles')]
    #[Groups(['course_read', 'affectation_read'])]
    private Collection $modules;

    public function __construct()
    {
        $this->courses = new ArrayCollection();
        $this->modules = new ArrayCollection();
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
            $course->setCourseTitle($this);
        }

        return $this;
    }

    public function removeCourse(Course $course): static
    {
        if ($this->courses->removeElement($course)) {
            // set the owning side to null (unless already changed)
            if ($course->getCourseTitle() === $this) {
                $course->setCourseTitle(null);
            }
        }

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
            $module->addCourseTitle($this);
        }

        return $this;
    }

    public function removeModule(Module $module): static
    {
        if ($this->modules->removeElement($module)) {
            $module->removeCourseTitle($this);
        }

        return $this;
    }
}
