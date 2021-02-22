<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TurbinesDatasRepository")
 * @ORM\Table(name="turbines_datas", indexes={
 *     @ORM\Index(name="idx_date", columns={"date"}),
 *     @ORM\Index(name="idx_hor", columns={"hour"}),
 *     @ORM\Index(name="idx_turbines_id", columns={"turbines_id"}),
 *     @ORM\Index(name="idx_timestamp", columns={"timestamp"}),
 * })
 */

class TurbinesDatas
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
    private $AvPower1min_W;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $AvPower5min_W;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $CurrentMagnitude_A;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $DamperActualPosition_Deg;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $DriveActive;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $DriveHealthy;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $FlowCoefficient;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Motor_rpm;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $OuputVoltage_V;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $OutputFrequency_Hz;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $PercentageLoad;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Power_kW;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Pressure_Pa;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $reactiveCurrent_A;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $RMSPressure_Pa;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $SetSpeed_Hz;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Vibration_mmps;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Flow1_pa;



    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Flow2_pa;


    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $WStaticPressure_Pa;



    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Temperature1;



    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $Waterlevel;





    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Turbines", inversedBy="turbines_datas")
     */
    private $turbines;


    public function getId():  ?\DateTimeInterface
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
    public function setTimestamp(\DateTimeInterface $timestamp): void
    {
        $this->timestamp = $timestamp;
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



    public function getActiveCurrentA(): ?float
    {
        return $this->ActiveCurrent_A;
    }

    public function setActiveCurrentA(float $ActiveCurrent_A): self
    {
        $this->ActiveCurrent_A = $ActiveCurrent_A;

        return $this;
    }

    public function getAUTOMATIC(): ?float
    {
        return $this->AUTOMATIC;
    }

    public function setAUTOMATIC(float $AUTOMATIC): self
    {
        $this->AUTOMATIC = $AUTOMATIC;

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

    public function getCurrentMagnitudeA(): ?float
    {
        return $this->CurrentMagnitude_A;
    }

    public function setCurrentMagnitudeA(float $CurrentMagnitude_A): self
    {
        $this->CurrentMagnitude_A = $CurrentMagnitude_A;

        return $this;
    }

    public function getDamperActualPositionDeg(): ?float
    {
        return $this->DamperActualPosition_Deg;
    }

    public function setDamperActualPositionDeg(float $DamperActualPosition_Deg): self
    {
        $this->DamperActualPosition_Deg = $DamperActualPosition_Deg;

        return $this;
    }


    /**
     * @return mixed
     */
    public function getDriveActive()
    {
        return $this->DriveActive;
    }

    /**
     * @param mixed $DriveActive
     */
    public function setDriveActive($DriveActive): void
    {
        $this->DriveActive = $DriveActive;
    }




    public function getDriveHealthy(): ?float
    {
        return $this->DriveHealthy;
    }

    public function setDriveHealthy(float $DriveHealthy): self
    {
        $this->DriveHealthy = $DriveHealthy;

        return $this;
    }

    public function getFlowCoefficient(): ?float
    {
        return $this->FlowCoefficient;
    }

    public function setFlowCoefficient(float $FlowCoefficient): self
    {
        $this->FlowCoefficient = $FlowCoefficient;

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
    public function getMotorRpm()
    {
        return $this->Motor_rpm;
    }

    /**
     * @param mixed $Motor_rpm
     */
    public function setMotorRpm($Motor_rpm): void
    {
        $this->Motor_rpm = $Motor_rpm;
    }

    /**
     * @return mixed
     */
    public function getOuputVoltageV()
    {
        return $this->OuputVoltage_V;
    }

    /**
     * @param mixed $OuputVoltage_V
     */
    public function setOuputVoltageV($OuputVoltage_V): void
    {
        $this->OuputVoltage_V = $OuputVoltage_V;
    }

    /**
     * @return mixed
     */
    public function getOutputFrequencyHz()
    {
        return $this->OutputFrequency_Hz;
    }

    /**
     * @param mixed $OutputFrequency_Hz
     */
    public function setOutputFrequencyHz($OutputFrequency_Hz): void
    {
        $this->OutputFrequency_Hz = $OutputFrequency_Hz;
    }

    /**
     * @return mixed
     */
    public function getPercentageLoad()
    {
        return $this->PercentageLoad;
    }

    /**
     * @param mixed $PercentageLoad
     */
    public function setPercentageLoad($PercentageLoad): void
    {
        $this->PercentageLoad = $PercentageLoad;
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
    public function getPressurePa()
    {
        return $this->Pressure_Pa;
    }

    /**
     * @param mixed $Pressure_Pa
     */
    public function setPressurePa($Pressure_Pa): void
    {
        $this->Pressure_Pa = $Pressure_Pa;
    }

    /**
     * @return mixed
     */
    public function getReactiveCurrentA()
    {
        return $this->reactiveCurrent_A;
    }

    /**
     * @param mixed $reactiveCurrent_A
     */
    public function setReactiveCurrentA($reactiveCurrent_A): void
    {
        $this->reactiveCurrent_A = $reactiveCurrent_A;
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
    public function getSetSpeedHz()
    {
        return $this->SetSpeed_Hz;
    }

    /**
     * @param mixed $SetSpeed_Hz
     */
    public function setSetSpeedHz($SetSpeed_Hz): void
    {
        $this->SetSpeed_Hz = $SetSpeed_Hz;
    }

    /**
     * @return mixed
     */
    public function getVibrationMmps()
    {
        return $this->Vibration_mmps;
    }

    /**
     * @param mixed $Vibration_mmps
     */
    public function setVibrationMmps($Vibration_mmps): void
    {
        $this->Vibration_mmps = $Vibration_mmps;
    }

    /**
     * @return mixed
     */
    public function getFlow1Pa()
    {
        return $this->Flow1_pa;
    }

    /**
     * @param mixed $Flow1_pa
     */
    public function setFlow1Pa($Flow1_pa): void
    {
        $this->Flow1_pa = $Flow1_pa;
    }

    /**
     * @return mixed
     */
    public function getFlow2Pa()
    {
        return $this->Flow2_pa;
    }

    /**
     * @param mixed $Flow2_pa
     */
    public function setFlow2Pa($Flow2_pa): void
    {
        $this->Flow2_pa = $Flow2_pa;
    }

    /**
     * @return mixed
     */
    public function getWStaticPressurePa()
    {
        return $this->WStaticPressure_Pa;
    }

    /**
     * @param mixed $WStaticPressure_Pa
     */
    public function setWStaticPressurePa($WStaticPressure_Pa): void
    {
        $this->WStaticPressure_Pa = $WStaticPressure_Pa;
    }

    /**
     * @return mixed
     */
    public function getTemperature1()
    {
        return $this->Temperature1;
    }

    /**
     * @param mixed $Temperature1
     */
    public function setTemperature1($Temperature1): void
    {
        $this->Temperature1 = $Temperature1;
    }

    /**
     * @return mixed
     */
    public function getWaterlevel()
    {
        return $this->Waterlevel;
    }

    /**
     * @param mixed $Waterlevel
     */
    public function setWaterlevel($Waterlevel): void
    {
        $this->Waterlevel = $Waterlevel;
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
