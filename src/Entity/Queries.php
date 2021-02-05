<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QueriesRepository")
 */
class Queries
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
    private $date;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $downloads;

    /**
     * @ORM\Column(type="string", length=545)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=545, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="integer", length=45)
     */
    private $size;

    /**
     * @ORM\Column(type="boolean", length=1)
     */
    private $active;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turbines", inversedBy="queries", cascade={"persist"})
     */
    private $turbines;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="queries", cascade={"persist"})
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\HistoricSearches", inversedBy="queries", cascade={"persist"})
     */
    private $historic_search;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDownloads(): ?int
    {
        return $this->downloads;
    }

    public function setDownloads(int $downloads): self
    {
        $this->downloads = $downloads;

        return $this;
    }

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

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

    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getTurbines()
    {
        return $this->turbines;
    }

    /**
     * @param mixed $turbines
     */
    public function setTurbines($turbines): void
    {
        $this->turbines = $turbines;
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

    /**
     * @return mixed
     */
    public function getHistoricSearch()
    {
        return $this->historic_search;
    }

    /**
     * @param mixed $historic_search
     */
    public function setHistoricSearch($historic_search): void
    {
        $this->historic_search = $historic_search;
    }


}
