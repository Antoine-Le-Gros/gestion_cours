<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    /**
     * @var Collection<int, CourseTitle>
     */
    #[ORM\ManyToMany(targetEntity: CourseTitle::class, inversedBy: 'tags')]
    private Collection $courseTitleId;

    public function __construct()
    {
        $this->courseTitleId = new ArrayCollection();
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
     * @return Collection<int, CourseTitle>
     */
    public function getCourseTitleId(): Collection
    {
        return $this->courseTitleId;
    }

    public function addCourseTitleId(CourseTitle $courseTitleId): static
    {
        if (!$this->courseTitleId->contains($courseTitleId)) {
            $this->courseTitleId->add($courseTitleId);
        }

        return $this;
    }

    public function removeCourseTitleId(CourseTitle $courseTitleId): static
    {
        $this->courseTitleId->removeElement($courseTitleId);

        return $this;
    }
}
