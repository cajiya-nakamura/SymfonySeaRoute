<?php

namespace App\Entity;

use App\Repository\ScheduleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ScheduleRepository::class)]
class Schedule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'schedules')]
    private ?Route $route_id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $start_date = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $end_date = null;

    #[ORM\Column]
    private ?int $start_hour = null;

    #[ORM\Column]
    private ?int $start_minute = null;

    #[ORM\Column]
    private ?int $end_hour = null;

    #[ORM\Column]
    private ?int $end_minute = null;

    #[ORM\OneToMany(targetEntity: Price::class, mappedBy: 'schedule_id')]
    private Collection $prices;

    #[ORM\ManyToOne]
    private ?Vehicle $vehicle_id = null;

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRouteId(): ?Route
    {
        return $this->route_id;
    }

    public function setRouteId(?Route $route_id): static
    {
        $this->route_id = $route_id;

        return $this;
    }

    public function getStartDate(): ?\DateTimeImmutable
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeImmutable $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeImmutable
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeImmutable $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }

    public function getStartHour(): ?int
    {
        return $this->start_hour;
    }

    public function setStartHour(int $start_hour): static
    {
        $this->start_hour = $start_hour;

        return $this;
    }

    public function getStartMinute(): ?int
    {
        return $this->start_minute;
    }

    public function setStartMinute(int $start_minute): static
    {
        $this->start_minute = $start_minute;

        return $this;
    }

    public function getEndHour(): ?int
    {
        return $this->end_hour;
    }

    public function setEndHour(int $end_hour): static
    {
        $this->end_hour = $end_hour;

        return $this;
    }

    public function getEndMinute(): ?int
    {
        return $this->end_minute;
    }

    public function setEndMinute(int $end_minute): static
    {
        $this->end_minute = $end_minute;

        return $this;
    }

    /**
     * @return Collection<int, Price>
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): static
    {
        if (!$this->prices->contains($price)) {
            $this->prices->add($price);
            $price->setScheduleId($this);
        }

        return $this;
    }

    public function removePrice(Price $price): static
    {
        if ($this->prices->removeElement($price)) {
            // set the owning side to null (unless already changed)
            if ($price->getScheduleId() === $this) {
                $price->setScheduleId(null);
            }
        }

        return $this;
    }

    public function getVehicleId(): ?Vehicle
    {
        return $this->vehicle_id;
    }

    public function setVehicleId(?Vehicle $vehicle_id): static
    {
        $this->vehicle_id = $vehicle_id;

        return $this;
    }
}
