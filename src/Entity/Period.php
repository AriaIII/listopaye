<?php

namespace App\Entity;

use App\Repository\PeriodRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PeriodRepository::class)]
class Period
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $type;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $startdate;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeInterface $enddate;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $monthlyPeriod;

    #[ORM\Column(type: 'integer')]
    private int $employee;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getStartdate(): ?\DateTimeInterface
    {
        return $this->startdate;
    }

    public function setStartdate(\DateTimeInterface $startdate): self
    {
        $this->startdate = $startdate;

        return $this;
    }

    public function getEnddate(): ?\DateTimeInterface
    {
        return $this->enddate;
    }

    public function setEnddate(\DateTimeInterface $enddate): self
    {
        $this->enddate = $enddate;

        return $this;
    }

    public function getMonthlyPeriod(): ?string
    {
        return $this->monthlyPeriod;
    }

    public function setMonthlyPeriod(string $monthlyPeriod): self
    {
        $this->monthlyPeriod = $monthlyPeriod;

        return $this;
    }

    public function getEmployee(): ?int
    {
        return $this->employee;
    }

    public function setEmployee(int $employee): self
    {
        $this->employee = $employee;

        return $this;
    }
}
