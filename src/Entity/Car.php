<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CarRepository")
 */
class Car
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $plate;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    private $color;

    /**
     * @ORM\Column(type="integer")
     */
    private $placemax;

    /**
     * @ORM\Column(type="datetime")
     */
    private $putintoservicedate;

    public function getId()
    {
        return $this->id;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }

    public function getPlate(): ?string
    {
        return $this->plate;
    }

    public function setPlate(string $plate): self
    {
        $this->plate = $plate;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getPlacemax(): ?int
    {
        return $this->placemax;
    }

    public function setPlacemax(int $placemax): self
    {
        $this->placemax = $placemax;

        return $this;
    }

    public function getPutintoservicedate(): ?\DateTimeInterface
    {
        return $this->putintoservicedate;
    }

    public function setPutintoservicedate(\DateTimeInterface $putintoservicedate): self
    {
        $this->putintoservicedate = $putintoservicedate;

        return $this;
    }
}
