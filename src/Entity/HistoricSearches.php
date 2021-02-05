<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\HistoricSearchesRepository")
 */
class HistoricSearches
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=512)
     */
    private $reason;

    /**
     * @ORM\Column(type="datetime")
     */
    private $init_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $end_date;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turbines", inversedBy="turbines", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="turbines_id", referencedColumnName="id")
     * })
     */
    private $turbines;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="historic_searches", cascade={"persist"})
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Queries", mappedBy="historic_search")
     */
    private $queries;


    public function __construct()
    {
        $this->created = new \DateTime();

    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getInitDate(): ?\DateTimeInterface
    {
        return $this->init_date;
    }

    public function setInitDate(\DateTimeInterface $init_date): self
    {
        $this->init_date = $init_date;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

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

        return $this->user->getName();
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


}
