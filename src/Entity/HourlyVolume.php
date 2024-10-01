<?php

namespace App\Entity;

use App\Repository\HourlyVolumeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HourlyVolumeRepository::class)]
class HourlyVolume
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    private ?float $volume = null;

    #[ORM\ManyToOne(inversedBy: 'hourlyVolumes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'hourlyVolumes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Week $week = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getVolume(): ?float
    {
        return $this->volume;
    }

    public function setVolume(float $volume): static
    {
        $this->volume = $volume;

        return $this;
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

    public function getWeek(): ?Week
    {
        return $this->week;
    }

    public function setWeek(?Week $week): static
    {
        $this->week = $week;

        return $this;
    }
}
