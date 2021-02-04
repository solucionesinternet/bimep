<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{

    /** @var TranslatorInterface */
    const STATUS_ON = 'on';
    const STATUS_OFF = 'off';

    // Fenido las constantes de texto que se usaran en la clase User
    const USUARIO_CREADO_CORRECTAMENTE = 'Usuario creado correctamente';
    const DESCONECTADO_CON_EXITO = 'Desconectado con éxito';


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $name_surnames;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="integer", length=10)
     */
    private $num_access;


    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=45, nullable=true)
     */
    private $cif;

    /**
     * @ORM\Column(type="boolean", length=1)
     */
    private $particular;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @ORM\Column(type="string")
     */
    private $status;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\BuoysFilesUsers", mappedBy="user")
     */
    private $buoys_files_users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\HistoricSearches", mappedBy="historic_searches")
     */
    private $historic_searches;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersHistoricSearches", mappedBy="inverters_historic_searches")
     */
    private $inverters_historic_searches;



    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Queries", mappedBy="queries")
     */
    private $queries;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\InvertersQueries", mappedBy="inverters_queries")
     */
    private $inverters_queries;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Profile", inversedBy="profile")
     */
    private $profile;


    /**
     * @var string The hashed password
     * @ORM\Column(type="datetime")
     */
    private $last_conection;

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $hash_change_password;

    /**
     * @var string The hashed password
     * @ORM\Column(type="datetime")
     */
    private $created;

    /**
     * @var string The hashed password
     * @ORM\Column(type="datetime")
     */
    private $modified;




    /**
     * @ORM\Column(type="string")
     */
    private $locale;


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

    /**
     * @return mixed
     */
    public function getHistoricSearches()
    {
        return $this->historic_searches;
    }

    /**
     * @param mixed $historic_searches
     */
    public function setHistoricSearches($historic_searches): void
    {
        $this->historic_searches = $historic_searches;
    }

    /**
     * @return mixed
     */
    public function getQueries()
    {
        return $this->queries;
    }

    /**
     * @param mixed $queries
     */
    public function setQueries($queries): void
    {
        $this->queries = $queries;
    }





    /**
     * User constructor.
     */
    public function __construct()
    {
        // Meto valores a los campos baneado y roles cada vez que se de de alta un usuario
        $this->status = self::STATUS_ON;
        $this->roles = ['ROLE_USER'];
        $this->last_conection = new \DateTime();
        $this->hash_change_password = '';
        $this->created = new \DateTime();
        $this->modified = new \DateTime();
        $this->num_access += 1;
        $this->setProfile(1);
        $this->setLocale('ESP');

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status): void
    {
        $this->status = $status;
        if (!in_array($status, array(self::STATUS_ON, self::STATUS_OFF))) {
            throw new \InvalidArgumentException('Invalid Status');
        }
        $this->status = $status;
    }

    /**
     * @return string
     */
    public function getLastConection(): ?\DateTimeInterface
    {
        return $this->last_conection;
    }

    /**
     * @param string $last_conection
     */
    public function setLastConection(\DateTimeInterface $last_conection): void
    {
        $this->last_conection = $last_conection;
    }

    /**
     * @return string
     */
    public function getHashChangePassword(): string
    {
        return $this->hash_change_password;
    }

    /**
     * @param string $hash_change_password
     */
    public function setHashChangePassword(string $hash_change_password): void
    {
        $this->hash_change_password = $hash_change_password;
    }

    /**
     * @return string
     */
    public function getCreated(): ?\DateTimeInterface
    {
        return $this->created;
    }

    /**
     * @param string $created
     */
    public function setCreated(\DateTimeInterface $created): self
    {
        $this->created = $created;
    }

    /**
     * @return string
     */
    public function getModified(): ?\DateTimeInterface
    {
        return $this->modified;
    }

    /**
     * @param string $modified
     */
    public function setModified(\DateTimeInterface $modified): void
    {
        $this->modified = $modified;
    }

    /**
     * @return mixed
     */
    public function getNameSurnames()
    {
        return $this->name_surnames;
    }

    /**
     * @param mixed $name_surnames
     */
    public function setNameSurnames($name_surnames): void
    {
        $this->name_surnames = $name_surnames;
    }

    /**
     * @return mixed
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param mixed $company
     */
    public function setCompany($company): void
    {
        $this->company = $company;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone): void
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getCif()
    {
        return $this->cif;
    }

    /**
     * @param mixed $cif
     */
    public function setCif($cif): void
    {
        $this->cif = $cif;
    }

    /**
     * @return mixed
     */
    public function getParticular(): ?bool
    {
        return $this->particular;
    }

    /**
     * @param mixed $particular
     */
    public function setParticular(bool $particular): self
    {
        $this->particular = $particular;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * @param mixed $profile
     */
    public function setProfile($profile): void
    {
        $this->profile = $profile;
    }

    /**
     * @return mixed
     */
    public function getNumAccess()
    {
        return $this->num_access;
    }

    /**
     * @param mixed $num_access
     */
    public function setNumAccess($num_access): void
    {
        $this->num_access = $num_access;
    }


    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->getNameSurnames();
    }




    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function __toString()
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param stringbuoys_files_users $locale
     */
    public function setLocale($locale): void
    {
        $this->locale = $locale;
    }

    /**
     * @return mixed
     */
    public function getInvertersHistoricSearches()
    {
        return $this->inverters_historic_searches;
    }

    /**
     * @param mixed $inverters_historic_searches
     */
    public function setInvertersHistoricSearches($inverters_historic_searches): void
    {
        $this->inverters_historic_searches = $inverters_historic_searches;
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
