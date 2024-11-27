<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\AffectationRepository;
use App\State\SemesterAffectationProvider;
use App\State\UserYearAffectationProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AffectationRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(security: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(security: "is_granted('ROLE_ADMIN')"),
        new Delete(security: "is_granted('ROLE_ADMIN')"),
        new GetCollection(
            uriTemplate: '/users/{userId}/{yearId}/affectations',
            normalizationContext: ['groups' => ['affectation_read_graph']],
            security: "is_granted('ROLE_USER')",
            provider: UserYearAffectationProvider::class
        ),
        new GetCollection(
            uriTemplate: '/semesters/{id}/affectations',
            normalizationContext: ['groups' => ['affectation_semester', 'affectation_read']],
            security: "is_granted('ROLE_USER')",
            provider: SemesterAffectationProvider::class
        ),
    ],
    normalizationContext: ['groups' => ['affectation_read']],
    denormalizationContext: ['groups' => ['affectation_write']],
    order: ['id' => 'ASC'],
)]
class Affectation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['affectation_read', 'course_read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    #[Groups(['affectation_read', 'affectation_read_graph'])]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    #[Groups(['affectation_read', 'course_read'])]
    private ?User $teacher = null;

    #[ORM\Column]
    #[Assert\GreaterThan(0)]
    #[Groups(['affectation_read', 'affectation_write', 'affectation_read_graph', 'affectation_semester'])]
    private ?int $numberGroupTaken = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCourse(): ?Course
    {
        return $this->course;
    }

    public function setCourse(?Course $course): static
    {
        $this->course = $course;

        return $this;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }

    public function getNumberGroupTaken(): ?int
    {
        return $this->numberGroupTaken;
    }

    public function setNumberGroupTaken(int $numberGroupTaken): static
    {
        $this->numberGroupTaken = $numberGroupTaken;

        return $this;
    }
}
