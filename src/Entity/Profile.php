<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProfileRepository")
 */
class Profile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $numDownloads;

    /**
    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $numTurbinesToView;

    /**
    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $numBuoysToView;

    /**
    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $numInvertersToView;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="profile", cascade={"persist","remove"}, orphanRemoval=true)
     */
    private $user;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumDownloads()
    {
        return $this->numDownloads;
    }

    /**
     * @param mixed $numDownloads
     */
    public function setNumDownloads($numDownloads): void
    {
        $this->numDownloads = $numDownloads;
    }

    /**
     * @return mixed
     */
    public function getNumTurbinesToView()
    {
        return $this->numTurbinesToView;
    }

    /**
     * @param mixed $numTurbinesToView
     */
    public function setNumTurbinesToView($numTurbinesToView): void
    {
        $this->numTurbinesToView = $numTurbinesToView;
    }

    /**
     * @return mixed
     */
    public function getNumBuoysToView()
    {
        return $this->numBuoysToView;
    }

    /**
     * @param mixed $numBuoysToView
     */
    public function setNumBuoysToView($numBuoysToView): void
    {
        $this->numBuoysToView = $numBuoysToView;
    }

    /**
     * @return mixed
     */
    public function getNumInvertersToView()
    {
        return $this->numInvertersToView;
    }

    /**
     * @param mixed $numInvertersToView
     */
    public function setNumInvertersToView($numInvertersToView): void
    {
        $this->numInvertersToView = $numInvertersToView;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }





    public function __toString()
    {
        return $this->name;
    }
}
