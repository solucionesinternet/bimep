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
    private $ActiveCurrent_A_media;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $AUTOMATIC;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Power_kW_media;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Power_kW_max;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RMSPressure_Pa_media;




    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RMSPressure_Pa_max;




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
    public function getActiveCurrentAMedia()
    {
        return $this->ActiveCurrent_A_media;
    }

    /**
     * @param mixed $ActiveCurrent_A_media
     */
    public function setActiveCurrentAMedia($ActiveCurrent_A_media): void
    {
        $this->ActiveCurrent_A_media = $ActiveCurrent_A_media;
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
    public function getPowerKWMedia()
    {
        return $this->Power_kW_media;
    }

    /**
     * @param mixed $Power_kW_media
     */
    public function setPowerKWMedia($Power_kW_media): void
    {
        $this->Power_kW_media = $Power_kW_media;
    }

    /**
     * @return mixed
     */
    public function getRMSPressurePaMedia()
    {
        return $this->RMSPressure_Pa_media;
    }

    /**
     * @param mixed $RMSPressure_Pa_media
     */
    public function setRMSPressurePaMedia($RMSPressure_Pa_media): void
    {
        $this->RMSPressure_Pa_media = $RMSPressure_Pa_media;
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

    /**
     * @return mixed
     */
    public function getPowerKWMax()
    {
        return $this->Power_kW_max;
    }

    /**
     * @param mixed $Power_kW_max
     */
    public function setPowerKWMax($Power_kW_max): void
    {
        $this->Power_kW_max = $Power_kW_max;
    }

    /**
     * @return mixed
     */
    public function getRMSPressurePaMax()
    {
        return $this->RMSPressure_Pa_max;
    }

    /**
     * @param mixed $RMSPressure_Pa_max
     */
    public function setRMSPressurePaMax($RMSPressure_Pa_max): void
    {
        $this->RMSPressure_Pa_max = $RMSPressure_Pa_max;
    }



}


