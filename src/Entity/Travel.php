<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TravelRepository")
 */
class Travel
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $starthour;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $startaddress;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $startcity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $endaddress;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $endcity;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $startzipcode;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $endzipcode;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreated;

    /**
     * @ORM\Column(type="string", length=40)
     */
    private $title;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="travels")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User")
     */
    private $passengers;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Car")
     * @ORM\JoinColumn(nullable=false)
     */
    private $car;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Question", mappedBy="travel")
     */
    private $questions;

    /**
     * @ORM\Column(type="integer")
     */
    private $passengersMax;


    public function __construct()
    {
        $this->passengers = new ArrayCollection();
        $this->questions = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getStarthour(): ?\DateTimeInterface
    {
        return $this->starthour;
    }

    public function setStarthour(\DateTimeInterface $starthour): self
    {
        $this->starthour = $starthour;

        return $this;
    }

    public function getStartaddress(): ?string
    {
        return $this->startaddress;
    }

    public function setStartaddress(string $startaddress): self
    {
        $this->startaddress = $startaddress;

        return $this;
    }

    public function getStartcity(): ?string
    {
        return $this->startcity;
    }

    public function setStartcity(string $startcity): self
    {
        $this->startcity = $startcity;

        return $this;
    }

    public function getEndaddress(): ?string
    {
        return $this->endaddress;
    }

    public function setEndaddress(string $endaddress): self
    {
        $this->endaddress = $endaddress;

        return $this;
    }

    public function getEndcity(): ?string
    {
        return $this->endcity;
    }

    public function setEndcity(string $endcity): self
    {
        $this->endcity = $endcity;

        return $this;
    }

    public function getStartzipcode(): ?string
    {
        return $this->startzipcode;
    }

    public function setStartzipcode(string $startzipcode): self
    {
        $this->startzipcode = $startzipcode;

        return $this;
    }

    public function getEndzipcode(): ?string
    {
        return $this->endzipcode;
    }

    public function setEndzipcode(string $endzipcode): self
    {
        $this->endzipcode = $endzipcode;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDatecreated(): ?\DateTimeInterface
    {
        return $this->datecreated;
    }

    public function setDatecreated(\DateTimeInterface $datecreated): self
    {
        $this->datecreated = $datecreated;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }


    /**
     * @return Collection|User[]
     */
    public function getPassengers(): Collection
    {
        return $this->passengers;
    }

    public function addPassenger(User $passenger): self
    {
        if (!$this->passengers->contains($passenger)) {
            $this->passengers[] = $passenger;
        }

        return $this;
    }

    public function removePassenger(User $passenger): self
    {
        if ($this->passengers->contains($passenger)) {
            $this->passengers->removeElement($passenger);
        }

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCar(): ?Car
    {
        return $this->car;
    }

    public function setCar(?Car $car): self
    {
        $this->car = $car;

        return $this;
    }

    /**
     * @return Collection|Question[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setTravel($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): self
    {
        if ($this->questions->contains($question)) {
            $this->questions->removeElement($question);
            // set the owning side to null (unless already changed)
            if ($question->getTravel() === $this) {
                $question->setTravel(null);
            }
        }

        return $this;
    }

    public function getPassengersMax(): ?int
    {
        return $this->passengersMax;
    }

    public function setPassengersMax(int $passengersMax): self
    {
        $this->passengersMax = $passengersMax;

        return $this;
    }

}
