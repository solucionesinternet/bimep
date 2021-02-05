<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuoysRepository")
 */
class Buoys
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $hs;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $tp;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $direction;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $speed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BuoysFiles", mappedBy="buoys", cascade="remove")
     */
    private $buoys_files;



    /**
     * BuoysFiles constructor.
     * @param $downloads
     */
    public function __construct()
    {
        $this->modified = new \DateTime();
    }


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

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    public function getHs(): ?string
    {
        return $this->hs;
    }

    public function setHs(?string $hs): self
    {
        $this->hs = $hs;

        return $this;
    }

    public function getTp(): ?string
    {
        return $this->tp;
    }

    public function setTp(?string $tp): self
    {
        $this->tp = $tp;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(?string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getSpeed(): ?string
    {
        return $this->speed;
    }

    public function setSpeed(?string $speed): self
    {
        $this->speed = $speed;

        return $this;
    }

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(\DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
    }
}
