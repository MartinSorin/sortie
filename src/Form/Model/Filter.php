<?php

namespace App\Form\Model;

use App\Repository\TripRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TripRepository::class)]
class Filter
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $campus;

    #[ORM\Column(type: 'string', length: 50)]
    private $search;

    #[ORM\Column(type: 'datetime')]
    private $start;

    #[ORM\Column(type: 'datetime')]
    private $end;

    #[ORM\Column(type: 'boolean')]
    private $organiser;

    #[ORM\Column(type: 'boolean')]
    private $registered;

    #[ORM\Column(type: 'boolean')]
    private $notRegistered;

    #[ORM\Column(type: 'boolean')]
    private $passed;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return Filter
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCampus()
    {
        return $this->campus;
    }

    /**
     * @param mixed $campus
     * @return Filter
     */
    public function setCampus($campus)
    {
        $this->campus = $campus;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSearch()
    {
        return $this->search;
    }

    /**
     * @param mixed $search
     * @return Filter
     */
    public function setSearch($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStart()
    {
        return $this->start;
    }

    /**
     * @param mixed $start
     * @return Filter
     */
    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @param mixed $end
     * @return Filter
     */
    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrganiser()
    {
        return $this->organiser;
    }

    /**
     * @param mixed $organiser
     * @return Filter
     */
    public function setOrganiser($organiser)
    {
        $this->organiser = $organiser;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistered()
    {
        return $this->registered;
    }

    /**
     * @param mixed $registered
     * @return Filter
     */
    public function setRegistered($registered)
    {
        $this->registered = $registered;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNotRegistered()
    {
        return $this->notRegistered;
    }

    /**
     * @param mixed $notRegistered
     * @return Filter
     */
    public function setNotRegistered($notRegistered)
    {
        $this->notRegistered = $notRegistered;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassed()
    {
        return $this->passed;
    }

    /**
     * @param mixed $passed
     * @return Filter
     */
    public function setPassed($passed)
    {
        $this->passed = $passed;
        return $this;
    }



}