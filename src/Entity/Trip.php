<?php

namespace App\Entity;

use App\Repository\TripRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    #[Assert\NotBlank(['message'=>'Le nom de la sortie est obligatoire.'])]
    #[Assert\Length(['min'=>3,'minMessage' => 'Le nom ne peut avoir moins de 3 caractères','max'=>50, 'maxMessage' => 'Le nom ne peut avoir plus de 100 caractères.'])]
    private $name;

    #[ORM\Column(type: 'datetime')]
    #[Assert\NotBlank(message: "Votre sortie doit avoir une date et une heure de début.")]
    #[Assert\GreaterThan("today", message: "La date de début de l'activité doit être postérieure à aujourd'hui.")]
    private $dateTimeStart;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(['message'=>'La durée de la sortie est obligatoire.'])]
    #[Assert\GreaterThanOrEqual(30, message: 'durée de la sortie 30 minutes minimum.')]
    private $duration;

    #[ORM\Column(type: 'date')]
    #[Assert\NotBlank(message: "Votre sortie doit avoir une date limite d'inscription.")]
    #[Assert\LessThan(propertyPath:"dateTimeStart", message: "La date limite d'inscription doit être antérieure à la date de début.")]
    #[Assert\GreaterThanOrEqual("today", message: "La date limite d'inscription doit être postérieure à aujourd'hui.")]
    private $dateLimitInscription;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotBlank(['message'=>'Le nombre de places est obligatoire.'])]
    #[Assert\LessThan(500, message: 'le nombre maximum de participant doit être de 500.')]
    #[Assert\GreaterThanOrEqual(5, message: 'le nombre minimum de participant doit être de 5.')]
    private $nbInscriptionsMax;

    #[ORM\Column(type: 'text')]
    #[Assert\NotBlank(['message'=>'Description de la sortie obligatoire.'])]
    private $infoTrip;

    #[ORM\ManyToOne(targetEntity: State::class, inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private $state;

    #[ORM\ManyToMany(targetEntity: Participant::class, inversedBy: 'trips')]
    private $isRegistered;

    #[ORM\ManyToOne(targetEntity: Participant::class, inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private $organiser;

    #[ORM\ManyToOne(targetEntity: Campus::class, inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private $siteOrganiser;

    #[ORM\ManyToOne(targetEntity: Place::class, inversedBy: 'trips')]
    #[ORM\JoinColumn(nullable: false)]
    private $place;

    public function __construct()
    {
        $this->isRegistered = new ArrayCollection();
    }

   
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDateTimeStart(): ?\DateTimeInterface
    {
        return $this->dateTimeStart;
    }

    public function setDateTimeStart(\DateTimeInterface $dateTimeStart): self
    {
        $this->dateTimeStart = $dateTimeStart;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getDateLimitInscription(): ?\DateTimeInterface
    {
        return $this->dateLimitInscription;
    }

    public function setDateLimitInscription(\DateTimeInterface $dateLimitInscription): self
    {
        $this->dateLimitInscription = $dateLimitInscription;

        return $this;
    }

    public function getNbInscriptionsMax(): ?int
    {
        return $this->nbInscriptionsMax;
    }

    public function setNbInscriptionsMax(int $nbInscriptionsMax): self
    {
        $this->nbInscriptionsMax = $nbInscriptionsMax;

        return $this;
    }

    public function getInfoTrip(): ?string
    {
        return $this->infoTrip;
    }

    public function setInfoTrip(string $infoTrip): self
    {
        $this->infoTrip = $infoTrip;

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return Collection<int, Participant>
     */
    public function getIsRegistered(): Collection
    {
        return $this->isRegistered;
    }

    public function addIsRegistered(Participant $isRegistered): self
    {
        if (!$this->isRegistered->contains($isRegistered)) {
            $this->isRegistered[] = $isRegistered;
        }

        return $this;
    }

    public function removeIsRegistered(Participant $isRegistered): self
    {
        $this->isRegistered->removeElement($isRegistered);

        return $this;
    }

    public function getOrganiser(): ?Participant
    {
        return $this->organiser;
    }

    public function setOrganiser(?Participant $organiser): self
    {
        $this->organiser = $organiser;

        return $this;
    }

    public function getSiteOrganiser(): ?Campus
    {
        return $this->siteOrganiser;
    }

    public function setSiteOrganiser(?Campus $siteOrganiser): self
    {
        $this->siteOrganiser = $siteOrganiser;

        return $this;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): self
    {
        $this->place = $place;

        return $this;
    }


}
