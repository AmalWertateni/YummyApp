<?php

namespace App\Entity;

use App\Repository\RestaurantTableRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestaurantTableRepository::class)]
class RestaurantTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numeroTable = null;

    #[ORM\Column]
    private ?int $capacité = null;

    #[ORM\Column(length: 255)]
    private ?string $emplacement = null;

    #[ORM\Column(length: 30)]
    private ?string $état = null;

    /**
     * @var Collection<int, Reservation>
     */
    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'tableRestaurant')]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroTable(): ?int
    {
        return $this->numeroTable;
    }

    public function setNumeroTable(int $numeroTable): static
    {
        $this->numeroTable = $numeroTable;

        return $this;
    }

    public function getCapacité(): ?int
    {
        return $this->capacité;
    }

    public function setCapacité(int $capacité): static
    {
        $this->capacité = $capacité;

        return $this;
    }

    public function getEmplacement(): ?string
    {
        return $this->emplacement;
    }

    public function setEmplacement(string $emplacement): static
    {
        $this->emplacement = $emplacement;

        return $this;
    }

    public function getétat(): ?string
    {
        return $this->état;
    }

    public function setétat(string $état): static
    {
        $this->état = $état;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setTableRestaurant($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getTableRestaurant() === $this) {
                $reservation->setTableRestaurant(null);
            }
        }

        return $this;
    }
}
