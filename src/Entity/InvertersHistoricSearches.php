<?php

namespace App\Entity;

use App\Repository\InvertersHistoricSearchesRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvertersHistoricSearchesRepository::class)
 */
class InvertersHistoricSearches
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Inverters", inversedBy="inverters", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inverters_id", referencedColumnName="id")
     * })
     */
    private $inverters;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="user", cascade={"persist"})
     */
    private $user;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersQueries", mappedBy="inverters_queries")
     */
    private $inverters_queries;





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




}
