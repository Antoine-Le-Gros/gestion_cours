<?php

namespace App\Entity;

use App\Repository\ExternalHourRecordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExternalHourRecordRepository::class)]
class ExternalHourRecord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $hours = null;

    #[ORM\ManyToOne(inversedBy: 'externalHourRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $teacher = null;

    #[ORM\ManyToOne(inversedBy: 'externalHourRecords')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Year $year = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHours(): ?float
    {
        return $this->hours;
    }

    public function setHours(float $hours): static
    {
        $this->hours = $hours;

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

    public function getYear(): ?Year
    {
        return $this->year;
    }

    public function setYear(?Year $year): static
    {
        $this->year = $year;

        return $this;
    }
}
