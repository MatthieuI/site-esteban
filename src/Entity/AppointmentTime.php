<?php

namespace App\Entity;

use App\Repository\AppointmentTimeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AppointmentTimeRepository::class)
 */
class AppointmentTime
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AppointmentType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $AppointmentType;

    /**
     * @ORM\Column(type="time")
     */
    private $time;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAppointmentType(): ?AppointmentType
    {
        return $this->AppointmentType;
    }

    public function setAppointmentType(?AppointmentType $AppointmentType): self
    {
        $this->AppointmentType = $AppointmentType;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(\DateTimeInterface $time): self
    {
        $this->time = $time;

        return $this;
    }
}
