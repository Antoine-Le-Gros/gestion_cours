<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use App\Repository\HourlyVolumeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: HourlyVolumeRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new Post(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Get(),
        new Patch(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
        new Delete(securityPostDenormalize: "is_granted('ROLE_ADMIN')"),
    ],
    normalizationContext: ['groups' => ['hourlyVolume_read']],
    denormalizationContext: ['groups' => ['hourlyVolume_write']],
    order: ['volume' => 'ASC'],
)]
class HourlyVolume
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['hourlyVolume_read'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\GreaterThanOrEqual(0)]
    #[Groups(['hourlyVolume_read', 'course_read', 'affectation_read_graph', 'courseTitle_info'])]
    private ?float $volume = null;

    #[ORM\ManyToOne(cascade: ['remove'], inversedBy: 'hourlyVolumes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Course $course = null;

    #[ORM\ManyToOne(inversedBy: 'hourlyVolumes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['course_read', 'hourlyVolume_graph_read', 'affectation_read_graph'])]
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
        $week->addHourlyVolume($this);

        return $this;
    }
}
