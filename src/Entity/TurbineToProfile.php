<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TurbineToProfileRepository")
 */
class TurbineToProfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @var \AppBundle\Entity\Turbines
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Turbines", inversedBy="theMappings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="turbines_id", referencedColumnName="id")
     * })
     */
    private $turbines;

    /**
     * @var \App\Entity\Profile
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="theMappings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="profile_id", referencedColumnName="id")
     * })
     */
    private $profile;


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\Turbines
     */
    public function getTurbines(): \App\Entity\Turbines
    {
        return $this->turbines;
    }

    /**
     * @param \App\Entity\Turbines $turbines
     */
    public function setTurbines(\App\Entity\Turbines $turbines): void
    {
        $this->turbines = $turbines;
    }

    /**
     * @return Profile
     */
    public function getProfile(): Profile
    {
        return $this->profile;
    }

    /**
     * @param Profile $profile
     */
    public function setProfile(Profile $profile): void
    {
        $this->profile = $profile;
    }

}
