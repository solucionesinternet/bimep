<?php

namespace App\Entity;

use App\Repository\InvertersToProfileRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=InvertersToProfileRepository::class)
 */
class InvertersToProfile
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;



    /**
     * @var \App\Entity\Inverters
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Inverters", inversedBy="theMappings")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="inverters_id", referencedColumnName="id")
     * })
     */
    private $inverters;



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
     * @return \App\Entity\Inverters
     */
    public function getInverters(): \App\Entity\Inverters
    {
        return $this->inverters;
    }

    /**
     * @param \AppBundle\Entity\Inverters $inverters
     */
    public function setInverters(\App\Entity\Inverters $inverters): void
    {
        $this->inverters = $inverters;
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
