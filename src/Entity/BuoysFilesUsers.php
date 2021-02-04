<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuoysFilesUsersRepository")
 */
class BuoysFilesUsers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $downloads;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @ORM\Column(type="datetime")
     */
    private $modified;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="buoys_files_users")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\BuoysFiles", inversedBy="buoys_files_users")
     * @ORM\JoinColumn(name="buoys_files_id", referencedColumnName="id", nullable=true)
     */
    private $buoys_files;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDownloads(): ?int
    {
        return $this->downloads;
    }

    public function setDownloads(int $downloads): self
    {
        $this->downloads = $downloads;

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
    public function getName(){
        $this->buoys_files->getFilename();
    }

    /**
     * @return mixed
     */
    public function getBuoysFiles()
    {
        return $this->buoys_files;
    }

    /**
     * @param mixed $buoys_files
     */
    public function setBuoysFiles($buoys_files): void
    {
        $this->buoys_files = $buoys_files;
    }




}
