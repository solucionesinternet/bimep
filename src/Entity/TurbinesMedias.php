<?php

namespace App\Entity;

use App\Repository\TurbinesMediasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TurbinesMediasRepository::class)
 */
class TurbinesMedias
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $timestamp;

    /**
     * @ORM\Column(type="date" )
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $hour;



    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ActiveCurrent_A;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $AUTOMATIC;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Power_kW;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RMSPressure_Pa;




    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turbines", inversedBy="turbines_datas")
     */
    private $turbines;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param mixed $timestamp
     */
    public function setTimestamp($timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     */
    public function setHour($hour): void
    {
        $this->hour = $hour;
    }

    /**
     * @return mixed
     */
    public function getActiveCurrentA()
    {
        return $this->ActiveCurrent_A;
    }

    /**
     * @param mixed $ActiveCurrent_A
     */
    public function setActiveCurrentA($ActiveCurrent_A): void
    {
        $this->ActiveCurrent_A = $ActiveCurrent_A;
    }

    /**
     * @return mixed
     */
    public function getAUTOMATIC()
    {
        return $this->AUTOMATIC;
    }

    /**
     * @param mixed $AUTOMATIC
     */
    public function setAUTOMATIC($AUTOMATIC): void
    {
        $this->AUTOMATIC = $AUTOMATIC;
    }

    /**
     * @return mixed
     */
    public function getPowerKW()
    {
        return $this->Power_kW;
    }

    /**
     * @param mixed $Power_kW
     */
    public function setPowerKW($Power_kW): void
    {
        $this->Power_kW = $Power_kW;
    }

    /**
     * @return mixed
     */
    public function getRMSPressurePa()
    {
        return $this->RMSPressure_Pa;
    }

    /**
     * @param mixed $RMSPressure_Pa
     */
    public function setRMSPressurePa($RMSPressure_Pa): void
    {
        $this->RMSPressure_Pa = $RMSPressure_Pa;
    }

    /**
     * @return mixed
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * @param mixed $created
     */
    public function setCreated($created): void
    {
        $this->created = $created;
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



}


