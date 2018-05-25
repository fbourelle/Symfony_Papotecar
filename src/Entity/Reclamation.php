<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ReclamationRepository")
 */
class Reclamation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reclamations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userlauncher;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reclamations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usertarget;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId()
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getUserlauncher(): ?User
    {
        return $this->userlauncher;
    }

    public function setUserlauncher(?User $userlauncher): self
    {
        $this->userlauncher = $userlauncher;

        return $this;
    }

    public function getUsertarget(): ?User
    {
        return $this->usertarget;
    }

    public function setUsertarget(?User $usertarget): self
    {
        $this->usertarget = $usertarget;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
