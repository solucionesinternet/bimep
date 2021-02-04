<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TurbinesRepository")
 */
class Turbines
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
     * @ORM\Column(type="string", length=50)
     */
    private $position;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $number;

    /**
     * @ORM\Column(type="boolean", length=1)
     */
    private $active;


    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoricSearches", mappedBy="turbines")
     */
    private $turbines;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Queries", mappedBy="turbines")
     */
    private $queries;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TurbinesFiles", mappedBy="turbines")
     */
    private $turbines_files;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TurbinesDatas", mappedBy="turbines")
     */
    private $turbines_datas;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TurbineToProfile", mappedBy="turbines", cascade={"persist"}, orphanRemoval=true)
     *
     */
    protected $theMappings;



    /**
     * Turbines constructor.
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

    public function setPosition(string $position): self
    {
        $this->position = $position;

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
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @param mixed $queries
     */
    public function setQueries($queries): void
    {
        $this->queries = $queries;
    }

    /**
     * @return mixed
     */
    public function getTurbinesFiles()
    {
        return $this->turbines_files;
    }

    /**
     * @param mixed $turbines_files
     */
    public function setTurbinesFiles($turbines_files): void
    {
        $this->turbines_files = $turbines_files;
    }

    /**
     * @return mixed
     */
    public function getTurbinesDatas()
    {
        return $this->turbines_datas;
    }

    /**
     * @param mixed $turbines_datas
     */
    public function setTurbinesDatas($turbines_datas): void
    {
        $this->turbines_datas = $turbines_datas;
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

    /**
     * @return mixed
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param mixed $number
     */
    public function setNumber($number): void
    {
        $this->number = $number;
    }



    public function __toString()
    {
        return $this->name;
    }

}
