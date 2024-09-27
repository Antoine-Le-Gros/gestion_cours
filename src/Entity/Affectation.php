<?php

namespace App\Entity;

use App\Repository\AffectationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AffectationRepository::class)]
class Affectation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'affectations')]
    private ?User $teacher = null;

    #[ORM\Column]
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
