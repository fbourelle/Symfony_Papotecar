<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PrivateMessagesRepository")
 */
class PrivateMessages
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userlauncherid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $usertargetid;

    /**
     * @ORM\Column(type="datetime")
     */
    private $datecreated;

    public function getId()
    {
        return $this->id;
    }

    public function getUserlauncherid(): ?User
    {
        return $this->userlauncherid;
    }

    public function setUserlauncherid(?User $userlauncherid): self
    {
        $this->userlauncherid = $userlauncherid;

        return $this;
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

    public function getUsertargetid(): ?User
    {
        return $this->usertargetid;
    }

    public function setUsertargetid(?User $usertargetid): self
    {
        $this->usertargetid = $usertargetid;

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
}
