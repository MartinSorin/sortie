<?php

namespace App\Entity;

use App\Repository\SortieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SortieRepository::class)]
class Trip
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $name;

    #[ORM\Column(type: 'datetime')]
    private $dateTimeStart;

    #[ORM\Column(type: 'time')]
    private $duration;

    #[ORM\Column(type: 'date')]
    private $dateLimitInscription;

    #[ORM\Column(type: 'integer')]
    private $nbInscriptionsMax;

    #[ORM\Column(type: 'text')]
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

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
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
