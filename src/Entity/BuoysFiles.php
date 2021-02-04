<?php

namespace App\Entity;

use Doctrine\DBAL\Types\DateType;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BuoysFilesRepository")
 */
class BuoysFiles
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_start;

    /**
     * @ORM\Column(type="date")
     */
    private $date_end;

    /**
     * @ORM\Column(type="integer", length=11)
     */
    private $downloads;

    /**
     * @ORM\Column(type="string", length=500)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $link;

    /**
     * @ORM\Column(type="integer")
     */
    private $size;

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


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\FilesCategories", inversedBy="files_categories")
     */
    private $files_categories;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BuoysFilesUsers", mappedBy="buoys_files_users")
     */
    private $buoys_files_users;





    /**
     * BuoysFiles constructor.
     * @param $downloads
     */
    public function __construct()
    {
        $this->downloads = 0;
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getDateStart()
    {
        return $this->date_start;
    }

    /**
     * @param mixed $date_start
     */
    public function setDateStart($date_start): void
    {
        $this->date_start = $date_start;
    }

    /**
     * @return mixed
     */
    public function getDateEnd()
    {
        return $this->date_end;
    }

    /**
     * @param mixed $date_end
     */
    public function setDateEnd($date_end): void
    {
        $this->date_end = $date_end;
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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): self
    {
        $this->filename = $filename;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): self
    {
        $this->link = $link;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(int $size): self
    {
        $this->size = $size;

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

    /**
     * @return mixed
     */
    public function getFilesCategories()
    {
        return $this->files_categories;
    }

    /**
     * @param mixed $files_categories
     */
    public function setFilesCategories($files_categories): void
    {
        $this->files_categories = $files_categories;
    }

    /**
     * @return mixed
     */
    public function getBuoysFilesUsers()
    {
        return $this->buoys_files_users;
    }

    /**
     * @param mixed $buoys_files_users
     */
    public function setBuoysFilesUsers($buoys_files_users): void
    {
        $this->buoys_files_users = $buoys_files_users;
    }





    public function __toString()
    {
        return $this->filename;
    }



}
