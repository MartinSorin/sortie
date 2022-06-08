<?php

namespace App\Entity;

use App\Repository\ParticipantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ParticipantRepository::class)]
class Participant implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(['message'=>'Le nom du participant est obligatoire.'])]
    #[Assert\Length(['max'=>50, 'maxMessage' => 'Le nom ne peut avoir plus de 50 caractères.'])]
    private $name;

    #[ORM\Column(type: 'string', length: 50)]
    #[Assert\NotBlank(['message'=>'Le prénom du participant est obligatoire.'])]
    #[Assert\Length(['max'=>50, 'maxMessage' => 'Le prénom ne peut avoir plus de 50 caractères.'])]
    private $firstname;

    #[ORM\Column(type: 'string', length: 20)]
    #[Assert\NotBlank(['message'=>'Le numéro de téléphone du participant est obligatoire.'])]
    #[Assert\Length(['min'=>10,'minMessage' => 'Le numéro de téléphone ne peut avoir moins de 10 caractères.'])]
    private $phone;

    #[ORM\Column(type: 'string', length: 180, unique: true)]
    #[Assert\NotBlank(['message'=>'L\'adresse email du participant est obligatoire.'])]
    #[Assert\Length(['max'=>180, 'maxMessage' => 'L\'adresse email ne peut avoir plus de 180 caractères.'])]
    private $email;

    #[ORM\Column(type: 'json')]
    private $roles = [];

    #[ORM\Column(type: 'string')]
    #[Assert\NotBlank(['message'=>'Le mot de passe du participant est obligatoire.'])]
    #[Assert\Length(['min'=>5,'minMessage' => 'Le mot de passe ne peut avoir moins de 5 caractères.'])]
    private $password;

    #[ORM\Column(type: 'boolean')]
    private $active;

    #[ORM\ManyToMany(targetEntity: Trip::class, mappedBy: 'isRegistered')]
    private $trips;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'participants')]
    #[Assert\NotBlank(['message'=>'Le campus est obligatoire.'])]
    #[ORM\JoinColumn(nullable: false)]
    private $isAffectedTo;

    #[ORM\Column(type: 'string', length: 50, nullable: true, unique: true)]
    #[Assert\NotBlank(['message'=>'Le pseudo du participant est obligatoire.'])]
    #[Assert\Length(['max'=>50, 'maxMessage' => 'Le pseudo ne peut avoir plus de 50 caractères.'])]
    private $username;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private $imageProfile;

    public function __construct()
    {
        $this->trips = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
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
    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Trip>
     */
    public function getTrips(): Collection
    {
        return $this->trips;
    }

    public function addTrip(Trip $trip): self
    {
        if (!$this->trips->contains($trip)) {
            $this->trips[] = $trip;
            $trip->addIsRegistered($this);
        }

        return $this;
    }

    public function removeTrip(Trip $trip): self
    {
        if ($this->trips->removeElement($trip)) {
            $trip->removeIsRegistered($this);
        }

        return $this;
    }

    public function getIsAffectedTo(): ?Campus
    {
        return $this->isAffectedTo;
    }

    public function setIsAffectedTo(?Campus $isAffectedTo): self
    {
        $this->isAffectedTo = $isAffectedTo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email or $this->username;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->username;
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
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudo(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getImageProfile(): ?string
    {
        return $this->imageProfile;
    }

    public function setImageProfile(?string $imageProfile): self
    {
        $this->imageProfile = $imageProfile;

        return $this;
    }
}
