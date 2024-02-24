<?php

namespace App\Entity;

use App\Repository\RouteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RouteRepository::class)]
class Route
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Port $depart_port = null;

    #[ORM\ManyToOne]
    private ?Port $arrival_port = null;

    #[ORM\OneToMany(targetEntity: Schedule::class, mappedBy: 'route_id')]
    private Collection $schedules;

    #[ORM\ManyToOne]
    private ?RouteCategory $category_id = null;

    public function __construct()
    {
        $this->schedules = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDepartPort(): ?Port
    {
        return $this->depart_port;
    }

    public function setDepartPort(?Port $depart_port): static
    {
        $this->depart_port = $depart_port;

        return $this;
    }

    public function getArrivalPort(): ?Port
    {
        return $this->arrival_port;
    }

    public function setArrivalPort(?Port $arrival_port): static
    {
        $this->arrival_port = $arrival_port;

        return $this;
    }

    /**
     * @return Collection<int, Schedule>
     */
    public function getSchedules(): Collection
    {
        return $this->schedules;
    }

    public function addSchedule(Schedule $schedule): static
    {
        if (!$this->schedules->contains($schedule)) {
            $this->schedules->add($schedule);
            $schedule->setRouteId($this);
        }

        return $this;
    }

    public function removeSchedule(Schedule $schedule): static
    {
        if ($this->schedules->removeElement($schedule)) {
            // set the owning side to null (unless already changed)
            if ($schedule->getRouteId() === $this) {
                $schedule->setRouteId(null);
            }
        }

        return $this;
    }

    public function getCategoryId(): ?RouteCategory
    {
        return $this->category_id;
    }

    public function setCategoryId(?RouteCategory $category_id): static
    {
        $this->category_id = $category_id;

        return $this;
    }
}
