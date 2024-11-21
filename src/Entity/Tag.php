<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Delete(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['tag_read']],
    denormalizationContext: ['groups' => ['tag_write']],
    order: ['name' => 'ASC'],
)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tag_read'])]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    #[Groups(['courseTitle_info', 'tag_read'])]
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
