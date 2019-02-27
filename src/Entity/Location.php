<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\LocationRepository")
 */
class Location
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $numStreet;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $region;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lat;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $lng;

    /**
    * @ORM\Column(type="string", length=255, nullable=true)
    */
    private $fullAdr;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumStreet(): ?string
    {
        return $this->numStreet;
    }

    public function setNumStreet(?string $numStreet): self
    {
        $this->numStreet = $numStreet;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): self
    {
        $this->street = $street;

        return $this;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    public function getFullAdr(): ?string
    {
        return $this->fullAdr;
    }

    public function setFullAdr(?string $fullAdr): self
    {
        $this->fullAdr = $fullAdr;

        return $this;
    }
}
