<?php

namespace App\Entity;

use App\Repository\InvertersQueriesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvertersQueriesRepository::class)
 */
class InvertersQueries
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
     * @ORM\Column(type="integer")
     */
    private $downloads;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inverters", inversedBy="inverters_queries", cascade={"persist"})
     */
    private $inverters;



    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="inverters_queries", cascade={"persist"})
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\InvertersHistoricSearches", inversedBy="inverters_queries", cascade={"persist"})
     */
    private $inverters_historic_search;



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
    public function getInverters()
    {
        return $this->inverters;
    }

    /**
     * @param mixed $inverters
     */
    public function setInverters($inverters): void
    {
        $this->inverters = $inverters;
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
    public function getInvertersHistoricSearch()
    {
        return $this->inverters_historic_search;
    }

    /**
     * @param mixed $inverters_historic_search
     */
    public function setInvertersHistoricSearch($inverters_historic_search): void
    {
        $this->inverters_historic_search = $inverters_historic_search;
    }


}
