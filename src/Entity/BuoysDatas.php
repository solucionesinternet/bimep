<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuoysDatasRepository")
 */
class BuoysDatas
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
    private $timestamp;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $hour;

    /**
     * @ORM\Column(type="float")
     */
    private $Airpressure;


    /**
     * @ORM\Column(type="float")
     */
    private $AirTemp;

    /**
     * @ORM\Column(type="float")
     */
    private $BatteryVolt;


    /**
     * @ORM\Column(type="float")
     */
    private $CurrDir;


    /**
     * @ORM\Column(type="float")
     */
    private $CurrSpeed;


    /**
     * @ORM\Column(type="float")
     */
    private $GlobalRadia;


    /**
     * @ORM\Column(type="float")
     */
    private $hm0;


    /**
     * @ORM\Column(type="float")
     */
    private $hm0a;


    /**
     * @ORM\Column(type="float")
     */
    private $hm0b;


    /**
     * @ORM\Column(type="float")
     */
    private $hmax;


    /**
     * @ORM\Column(type="float")
     */
    private $latitude;


    /**
     * @ORM\Column(type="float")
     */
    private $longitude;


    /**
     * @ORM\Column(type="float")
     */
    private $mdir;


    /**
     * @ORM\Column(type="float")
     */
    private $mdira;



    /**
     * @ORM\Column(type="float")
     */
    private $mdirb;


    /**
     * @ORM\Column(type="float")
     */
    private $tp;


    /**
     * @ORM\Column(type="float")
     */
    private $WaterTemp;


    /**
     * @ORM\Column(type="float")
     */
    private $WindDir;


    /**
     * @ORM\Column(type="float")
     */
    private $WindGust;


    /**
     * @ORM\Column(type="float")
     */
    private $WindSP;


    /**
     * @ORM\Column(type="string", length=45)
     */
    private $hs;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $direction;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $speed;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Buoys", inversedBy="buoys")
     */
    private $buoys;

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

    public function getHs(): ?string
    {
        return $this->hs;
    }

    public function setHs(string $hs): self
    {
        $this->hs = $hs;

        return $this;
    }

    public function getTp(): ?string
    {
        return $this->tp;
    }

    public function setTp(string $tp): self
    {
        $this->tp = $tp;

        return $this;
    }

    public function getDirection(): ?string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): self
    {
        $this->direction = $direction;

        return $this;
    }

    public function getSpeed(): ?string
    {
        return $this->speed;
    }

    public function setSpeed(string $speed): self
    {
        $this->speed = $speed;

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

    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    public function setModified(\DateTimeInterface $modified): self
    {
        $this->modified = $modified;

        return $this;
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
    public function getAirpressure()
    {
        return $this->Airpressure;
    }

    /**
     * @param mixed $Airpressure
     */
    public function setAirpressure($Airpressure): void
    {
        $this->Airpressure = $Airpressure;
    }

    /**
     * @return mixed
     */
    public function getAirTemp()
    {
        return $this->AirTemp;
    }

    /**
     * @param mixed $AirTemp
     */
    public function setAirTemp($AirTemp): void
    {
        $this->AirTemp = $AirTemp;
    }

    /**
     * @return mixed
     */
    public function getBatteryVolt()
    {
        return $this->BatteryVolt;
    }

    /**
     * @param mixed $BatteryVolt
     */
    public function setBatteryVolt($BatteryVolt): void
    {
        $this->BatteryVolt = $BatteryVolt;
    }

    /**
     * @return mixed
     */
    public function getCurrDir()
    {
        return $this->CurrDir;
    }

    /**
     * @param mixed $CurrDir
     */
    public function setCurrDir($CurrDir): void
    {
        $this->CurrDir = $CurrDir;
    }

    /**
     * @return mixed
     */
    public function getCurrSpeed()
    {
        return $this->CurrSpeed;
    }

    /**
     * @param mixed $CurrSpeed
     */
    public function setCurrSpeed($CurrSpeed): void
    {
        $this->CurrSpeed = $CurrSpeed;
    }

    /**
     * @return mixed
     */
    public function getGlobalRadia()
    {
        return $this->GlobalRadia;
    }

    /**
     * @param mixed $GlobalRadia
     */
    public function setGlobalRadia($GlobalRadia): void
    {
        $this->GlobalRadia = $GlobalRadia;
    }

    /**
     * @return mixed
     */
    public function getHm0()
    {
        return $this->hm0;
    }

    /**
     * @param mixed $hm0
     */
    public function setHm0($hm0): void
    {
        $this->hm0 = $hm0;
    }

    /**
     * @return mixed
     */
    public function getHm0a()
    {
        return $this->hm0a;
    }

    /**
     * @param mixed $hm0a
     */
    public function setHm0a($hm0a): void
    {
        $this->hm0a = $hm0a;
    }

    /**
     * @return mixed
     */
    public function getHm0b()
    {
        return $this->hm0b;
    }

    /**
     * @param mixed $hm0b
     */
    public function setHm0b($hm0b): void
    {
        $this->hm0b = $hm0b;
    }

    /**
     * @return mixed
     */
    public function getHmax()
    {
        return $this->hmax;
    }

    /**
     * @param mixed $hmax
     */
    public function setHmax($hmax): void
    {
        $this->hmax = $hmax;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @param mixed $latitude
     */
    public function setLatitude($latitude): void
    {
        $this->latitude = $latitude;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     */
    public function setLongitude($longitude): void
    {
        $this->longitude = $longitude;
    }

    /**
     * @return mixed
     */
    public function getMdir()
    {
        return $this->mdir;
    }

    /**
     * @param mixed $mdir
     */
    public function setMdir($mdir): void
    {
        $this->mdir = $mdir;
    }

    /**
     * @return mixed
     */
    public function getMdira()
    {
        return $this->mdira;
    }

    /**
     * @param mixed $mdira
     */
    public function setMdira($mdira): void
    {
        $this->mdira = $mdira;
    }

    /**
     * @return mixed
     */
    public function getMdirb()
    {
        return $this->mdirb;
    }

    /**
     * @param mixed $mdirb
     */
    public function setMdirb($mdirb): void
    {
        $this->mdirb = $mdirb;
    }

    /**
     * @return mixed
     */
    public function getWaterTemp()
    {
        return $this->WaterTemp;
    }

    /**
     * @param mixed $WaterTemp
     */
    public function setWaterTemp($WaterTemp): void
    {
        $this->WaterTemp = $WaterTemp;
    }

    /**
     * @return mixed
     */
    public function getWindDir()
    {
        return $this->WindDir;
    }

    /**
     * @param mixed $WindDir
     */
    public function setWindDir($WindDir): void
    {
        $this->WindDir = $WindDir;
    }

    /**
     * @return mixed
     */
    public function getWindGust()
    {
        return $this->WindGust;
    }

    /**
     * @param mixed $WindGust
     */
    public function setWindGust($WindGust): void
    {
        $this->WindGust = $WindGust;
    }

    /**
     * @return mixed
     */
    public function getWindSP()
    {
        return $this->WindSP;
    }

    /**
     * @param mixed $WindSP
     */
    public function setWindSP($WindSP): void
    {
        $this->WindSP = $WindSP;
    }

    /**
     * @return mixed
     */
    public function getBuoys()
    {
        return $this->buoys;
    }

    /**
     * @param mixed $buoys
     */
    public function setBuoys($buoys): void
    {
        $this->buoys = $buoys;
    }



}
