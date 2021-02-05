<?php

namespace App\Entity;

use App\Repository\InvertersDatasRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvertersDatasRepository::class)
 */
class InvertersDatas
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $ActiveCurrent_A;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $AvPower1min_W;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $AvPower5min_W;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $DCLinkVoltage_V;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $DriveHealthy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $OutputVoltage_V;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $OutputFrequency_Hz;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Power_kW;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ReactiveCurrent_A;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $ReactivePower_kVAr;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RoomHumidity;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RoomPressure1_Pa;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RoomPressure2_Pa;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RoomTemperature;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Inverters", inversedBy="inverters_datas")
     */
    private $inverters;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTimestamp(): ?\DateTimeInterface
    {
        return $this->timestamp;
    }

    public function setTimestamp(\DateTimeInterface $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
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

    public function getHour(): ?string
    {
        return $this->hour;
    }

    public function setHour(string $hour): self
    {
        $this->hour = $hour;

        return $this;
    }

    public function getActiveCurrentA(): ?float
    {
        return $this->ActiveCurrent_A;
    }

    public function setActiveCurrentA(?float $ActiveCurrent_A): self
    {
        $this->ActiveCurrent_A = $ActiveCurrent_A;

        return $this;
    }

    public function getAvPower1minW(): ?float
    {
        return $this->AvPower1min_W;
    }

    public function setAvPower1minW(float $AvPower1min_W): self
    {
        $this->AvPower1min_W = $AvPower1min_W;

        return $this;
    }

    public function getAvPower5minW(): ?float
    {
        return $this->AvPower5min_W;
    }

    public function setAvPower5minW(float $AvPower5min_W): self
    {
        $this->AvPower5min_W = $AvPower5min_W;

        return $this;
    }

    public function getDCLinkVoltageV(): ?float
    {
        return $this->DCLinkVoltage_V;
    }

    public function setDCLinkVoltageV(float $DCLinkVoltage_V): self
    {
        $this->DCLinkVoltage_V = $DCLinkVoltage_V;

        return $this;
    }

    public function getDriveHealthy(): ?bool
    {
        return $this->DriveHealthy;
    }

    public function setDriveHealthy(bool $DriveHealthy): self
    {
        $this->DriveHealthy = $DriveHealthy;

        return $this;
    }

    public function getOutputFrequencyHz(): ?float
    {
        return $this->OutputFrequency_Hz;
    }

    public function setOutputFrequencyHz(float $OutputFrequency_Hz): self
    {
        $this->OutputFrequency_Hz = $OutputFrequency_Hz;

        return $this;
    }

    public function getOutputVoltageV(): ?float
    {
        return $this->OutputVoltage_V;
    }

    public function setOutputVoltageV(float $OutputVoltage_V): self
    {
        $this->OutputVoltage_V = $OutputVoltage_V;

        return $this;
    }

    public function getPowerKW(): ?float
    {
        return $this->Power_kW;
    }

    public function setPowerKW(float $Power_kW): self
    {
        $this->Power_kW = $Power_kW;

        return $this;
    }

    public function getReactiveCurrentA(): ?float
    {
        return $this->ReactiveCurrent_A;
    }

    public function setReactiveCurrentA(float $ReactiveCurrent_A): self
    {
        $this->ReactiveCurrent_A = $ReactiveCurrent_A;

        return $this;
    }

    public function getReactivePowerKVAr(): ?float
    {
        return $this->ReactivePower_kVAr;
    }

    public function setReactivePowerKVAr(float $ReactivePower_kVAr): self
    {
        $this->ReactivePower_kVAr = $ReactivePower_kVAr;

        return $this;
    }

    public function getRoomPressure1Pa(): ?float
    {
        return $this->RoomPressure1_Pa;
    }

    public function setRoomPressure1Pa(float $RoomPressure1_Pa): self
    {
        $this->RoomPressure1_Pa = $RoomPressure1_Pa;

        return $this;
    }

    public function getRoomPressure2Pa(): ?float
    {
        return $this->RoomPressure2_Pa;
    }

    public function setRoomPressure2Pa(float $RoomPressure2_Pa): self
    {
        $this->RoomPressure2_Pa = $RoomPressure2_Pa;

        return $this;
    }

    public function getRoomHumidity(): ?float
    {
        return $this->RoomHumidity;
    }

    public function setRoomHumidity(float $RoomHumidity): self
    {
        $this->RoomHumidity = $RoomHumidity;

        return $this;
    }

    public function getRoomTemperature(): ?float
    {
        return $this->RoomTemperature;
    }

    public function setRoomTemperature(float $RoomTemperature): self
    {
        $this->RoomTemperature = $RoomTemperature;

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


}
