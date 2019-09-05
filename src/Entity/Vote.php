<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;

/**
 * @ORM\Entity(repositoryClass="App\Repository\VoteRepository")
 */
class Vote
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Conference")
     * @JoinColumn(name="conference_id", referencedColumnName="id")
     */
    private $conference;

    /**
     * @ORM\ManyToOne(targetEntity="User")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getConference()
    {
        return $this->conference;
    }

    public function setConference($conference): self
    {
        $this->conference = $conference;
        return $this;
    }

    public function addConference($conference)
    {
        $this->conference[] = $conference;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user): self
    {
        $this->user = $user;
        return $this;
    }

    public function addUser($user)
    {
        $this->user[] = $user;
    }

}
