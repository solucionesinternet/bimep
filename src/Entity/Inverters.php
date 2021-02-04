<?php

namespace App\Entity;

use App\Repository\InvertersRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvertersRepository::class)
 */
class Inverters
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
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersDatas", mappedBy="inverters")
     */
    private $inverters_datas;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersFiles", mappedBy="inverters")
     */
    private $inverters_files;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersHistoricSearches", mappedBy="inverters")
     */
    private $inverters;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersQueries", mappedBy="inverters")
     */
    private $inverters_queries;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersToProfile", mappedBy="inverters", cascade={"persist"}, orphanRemoval=true)
     *
     */
    protected $theMappings;



    /**
     * Inverters constructor.
     */
    public function __construct()
    {
        // Meto valores a los campos baneado y roles cada vez que se de de alta un usuario
        $this->created = new \DateTime();

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

    public function getPosition(): ?string
    {
        return $this->position;
    }

    public function setPosition(?string $position): self
    {
        $this->position = $position;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(?int $number): self
    {
        $this->number = $number;

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
    public function getInvertersDatas()
    {
        return $this->inverters_datas;
    }

    /**
     * @param mixed $inverters_datas
     */
    public function setInvertersDatas($inverters_datas): void
    {
        $this->inverters_datas = $inverters_datas;
    }

    /**
     * @return mixed
     */
    public function getInvertersFiles()
    {
        return $this->inverters_files;
    }

    /**
     * @param mixed $inverters_files
     */
    public function setInvertersFiles($inverters_files): void
    {
        $this->inverters_files = $inverters_files;
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
    public function getInvertersQueries()
    {
        return $this->inverters_queries;
    }

    /**
     * @param mixed $inverters_queries
     */
    public function setInvertersQueries($inverters_queries): void
    {
        $this->inverters_queries = $inverters_queries;
    }

    /**
     * @return mixed
     */
    public function getTheMappings()
    {
        return $this->theMappings;
    }

    /**
     * @param mixed $theMappings
     */
    public function setTheMappings($theMappings): void
    {
        $this->theMappings = $theMappings;
    }




}
